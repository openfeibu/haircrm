<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MailAccountRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class MailAccountRepository extends BaseRepository implements MailAccountRepositoryInterface
{

    public function model()
    {
        return config('model.mail.mail_account.model');
    }
    public function getAll()
    {
        return $this->model->orderBy('id','desc')->get();
    }
}