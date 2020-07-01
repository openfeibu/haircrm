<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MailScheduleReportRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class MailScheduleReportRepository extends BaseRepository implements MailScheduleReportRepositoryInterface
{

    public function model()
    {
        return config('model.mail.mail_schedule_report.model');
    }
}