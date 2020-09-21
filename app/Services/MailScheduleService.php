<?php
namespace App\Services;

use App\Models\MailAccount;
use App\Models\MailSchedule;
use App\Models\MailScheduleMailAccount;
use App\Models\MailScheduleMailTemplate;
use App\Models\MailScheduleReport;
use App\Models\MailTemplate;
use GuzzleHttp\Client;
use App\Exceptions\OutputServerMessageException;
use Swift_Mailer;
use Swift_SmtpTransport;
use Log,Mail;
use Egulias\EmailValidator\Exception\InvalidEmail;
use Egulias\EmailValidator\Validation\EmailValidation;

class MailScheduleService
{
    public function __construct()
    {

    }

    public function send()
    {
        /** 定时任务
         *  每次只能执行一个任务
         *  mail_count = send_count 则已经发完
         *  last_at ,最新一次发送时间，interval 间隔秒，per_hour_mail 每小时最多几封邮件
         *  $now, 现在时间，跟 $last_at差别 < $interval，不发送
         *  per_hour_mail < mail_schedule_reports 表，sent = 1 ，一个小时内发的数量, 不发送
         */

        if(date('G')>12 && date('G')<18){
            return '只在早上12点前，晚上6点后发送,其他时间段内不发送.';
        }

        $schedule = MailSchedule::where('active',1)->whereNotIn('status',['complete'])->first();
        //$schedule = MailSchedule::whereNotIn('status',['complete'])->first();
        if(!$schedule)
        {
            return '没有任务';
        }
        if($schedule->mail_count == $schedule->send_count)
        {
            MailSchedule::where('id',$schedule->id)->update(['status' => 'complete']);
            return '任务已完成';
        }

        //现在时间，跟 $last_at差别 < $interval，不发送
        if($schedule->last_at && (strtotime("now") - strtotime($schedule->last_at) < $schedule->interval))
        {
            return '时间间隔限制';
        }
        //过去一小时发送数量
        $last_hour_mail_count = MailScheduleReport::where('mail_schedule_id',$schedule->id)->where('send_at','>',date('Y-m-d H:i:s',strtotime('-1 hour')))->count();
        if($last_hour_mail_count >= $schedule->per_hour_mail)
        {
            return '每小时发送量限制';
        }

        $mail_schedule_report = MailScheduleReport::where('mail_schedule_id',$schedule->id)->where('sent',0)->orderBy('id','desc')->first();

        if(!$mail_schedule_report)
        {
            MailSchedule::where('id',$schedule->id)->update(['status' => 'complete']);
            return '任务中没有未完成的发送';
        }
        $email = trim($mail_schedule_report->email);

        //按最少发送量的账号发送
        $mail_account_id = MailScheduleMailAccount::where('mail_schedule_id',$schedule->id)->orderBy('send_count','asc')->orderBy('id','asc')->value('mail_account_id');

        if(!$mail_account_id)
        {
            return '任务无配置账号';
        }

        $mail_account = MailAccount::where('id',$mail_account_id)->first();
        if(!$mail_account)
        {
            return '账号'+$mail_account_id+'不存在';
        }

        //按最少发送量的模板发送
        $mail_template_id = MailScheduleMailTemplate::where('mail_schedule_id',$schedule->id)->orderBy('send_count','asc')->orderBy('id','asc')->value('mail_template_id');

        if(!$mail_template_id)
        {
            return '任务无配置模板';
        }

        $mail_template = MailTemplate::where('id',$mail_template_id)->first();
        if(!$mail_template)
        {
            return '模板'+$mail_template_id+'不存在';
        }

        $html = $mail_template->content;
        $html = replace_image_url($html,config('app.image_url'));
        $name = $mail_schedule_report->name ?? '';
        $html=str_replace('{$name}',$name,$html);

        MailSchedule::where('id',$schedule->id)->update(['status' => 'sending']);


        try
        {
            $validator = validator(['email' => $email], ['email' => 'email']);
            $emailArr = explode('@', $email);
            if ($validator->fails()) {
                $send = '邮箱错误';
                $status = 'failed';
                $success_count = $schedule->success_coun;
                $failed_count = $schedule->failed_count + 1;
            }
            else if(!in_array('@'.strtolower($emailArr[1]), config('common.overseas_email_suffix'))){
                $send = '邮箱地址非海外常用地址';
                $status = 'failed';
                $success_count = $schedule->success_coun;
                $failed_count = $schedule->failed_count + 1;
            }
            else{
                $backup = Mail::getSwiftMailer();
                /** 邮箱多切换 */
                // 设置邮箱账号
                $transport = new Swift_SmtpTransport($mail_account->host, $mail_account->port, $mail_account->encryption);
                $transport->setUsername($mail_account->username);
                $transport->setPassword($mail_account->password);

                $mailer = new Swift_Mailer($transport);

                Mail::setSwiftMailer($mailer);
                $send = Mail::html($html, function($message) use($mail_schedule_report,$mail_account,$mail_template) {
                    $message->from($mail_account->from_address,$mail_account->from_name);
                    $message->subject($mail_template->subject);
                    $message->to($mail_schedule_report->email);
                });
                $status = 'success';
                $success_count = $schedule->success_count+1;
                $failed_count = $schedule->failed_count;
                // 发送后还原
                Mail::setSwiftMailer($backup);
            }


            $send_at = date('Y-m-d H:i:s');
            MailScheduleReport::where('id',$mail_schedule_report->id)->update([
                'mail_template_id' => $mail_template->id,
                'mail_account_id' => $mail_account->id,
                'mail_template_name' => $mail_template->name,
                'mail_account_username' => $mail_account->username,
                'status' => $status,
                'sent' => 1,
                'mail_return' => $send,
                'send_at' => $send_at
            ]);

            MailScheduleMailAccount::where('mail_schedule_id',$schedule->id)->where('mail_account_id',$mail_account_id)->increment('send_count');
            MailScheduleMailTemplate::where('mail_schedule_id',$schedule->id)->where('mail_template_id',$mail_template_id)->increment('send_count');
            $send_count = $schedule->send_count+1;
            $status = $send_count == $schedule->mail_count ? 'complete' : $schedule->status;
            MailSchedule::where('id',$schedule->id)->update([
                'last_at' => $send_at,
                'send_count' => $send_count,
                'success_count' => $success_count,
                'failed_count' => $failed_count,
                'status' => $status
            ]);

            return $send;
        }

        catch (Exception $e) {
            var_dump($e);exit;
        }
    }
}