<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MailScheduleRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class MailScheduleRepository extends BaseRepository implements MailScheduleRepositoryInterface
{

    public function model()
    {
        return config('model.mail.mail_schedule.model');
    }
    public function getAll()
    {
        return $this->model->orderBy('id','desc')->get();
    }

}