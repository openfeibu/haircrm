<?php

namespace App\Console\Commands;

use App\Models\Onbuy\Onbuy;
use App\Services\MailScheduleService;
use App\Services\Onbuy\ListingService;
use Illuminate\Console\Command;
use Log;

class PricingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pricing:auto';

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
        $onbuy_list = Onbuy::where('status',1)->get();
		Log::info('price_auto start');
        foreach ($onbuy_list as $onbuy)
        {
            $list_service = new ListingService($onbuy['seller_id']);
			Log::info('price_auto seller_id:'.$onbuy['seller_id']);           
		   $list_service->automatic();
        }
    }
}
