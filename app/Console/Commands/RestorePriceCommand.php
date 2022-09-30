<?php

namespace App\Console\Commands;

use App\Services\MailScheduleService;
use App\Services\Onbuy\ListingService;
use Illuminate\Console\Command;

class RestorePriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore_price:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时竞价';

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
        app(ListingService::class)->restorePrice();
    }
}
