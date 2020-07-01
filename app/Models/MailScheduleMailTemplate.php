<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class MailScheduleMailTemplate extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.mail.mail_schedule_mail_template';

    public $timestamps = false;
}