<?php

namespace App\Console\Commands;

use App\Services\MailScheduleService;
use App\Services\Onbuy\OrderService;
use Illuminate\Console\Command;

class OnbuyOrderSyncUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onbuy_order_sync_update:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时同步更新Onbuy订单';

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
        $order_service = new OrderService();
        $order_service->automaticSyncUpdate();
    }
}
