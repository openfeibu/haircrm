<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class NewCustomer extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.customer.new_customer';

    public $appends = ['mark_desc','mail_report_date'];

    public function getMarkDescAttribute()
    {
        return isset($this->attributes['mark']) ? trans('new_customer.mark.'.$this->attributes['mark']) : '';
    }

    public function getMailReportDateAttribute()
    {
        if(isset($this->attributes['email']) && $this->attributes['email']) {
            $report = MailScheduleReport::where('email',$this->attributes['email'])->where('sent',1)->orderBy('id','desc')->first();
            if($report)
            {
                if($report->status =='failed')
                {
                    $html = '<span style="color:#FF5722">';
                }else{
                    $html = '<span>';
                }
                $html .= $report->send_at. $report->mail_return."</span>";
                return $html;
            }
            return '';
        }else{
            return '';
        }
    }
}