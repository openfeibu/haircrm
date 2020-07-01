<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class MailSchedule extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.mail.mail_schedule';

    protected $appends = ['status_desc'];

    public function getStatusDescAttribute()
    {
        return isset($this->attributes['status']) ? trans('mail_schedule.status.'.$this->attributes['status']) : '';
    }
    public function accounts()
    {
        return $this->belongsToMany(config('model.mail.mail_account.model'),'mail_schedule_mail_account');
    }
    public function templates()
    {
        return $this->belongsToMany(config('model.mail.mail_template.model'),'mail_schedule_mail_template');
    }

}