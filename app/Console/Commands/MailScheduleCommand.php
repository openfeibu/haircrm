<?php

namespace App\Console\Commands;

use App\Services\MailScheduleService;
use Illuminate\Console\Command;

class MailScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_schedule:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '邮箱定时发送';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        app(MailScheduleService::class)->send();
    }
}
