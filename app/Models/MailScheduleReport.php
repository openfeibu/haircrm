<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class MailScheduleReport extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.mail.mail_schedule_report';

    public $timestamps = false;

    protected $appends = ['status_desc','sent_desc'];

    public function getStatusDescAttribute()
    {
        return isset($this->attributes['status']) ? trans('mail_schedule_report.status.'.$this->attributes['status']) : '';
    }
    public function getSentDescAttribute()
    {
        return isset($this->attributes['sent']) ? trans('app.yes_no.'.$this->attributes['sent']) : '';
    }
}