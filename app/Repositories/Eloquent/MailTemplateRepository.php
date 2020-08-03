<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MailTemplateRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class MailTemplateRepository extends BaseRepository implements MailTemplateRepositoryInterface
{

    public function model()
    {
        return config('model.mail.mail_template.model');
    }
    public function getAll()
    {
        return $this->model->where('active',1)->orderBy('id','desc')->get();
    }
}