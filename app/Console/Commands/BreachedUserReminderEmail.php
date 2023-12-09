<?php

namespace App\Console\Commands;

use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\Customer;
use App\Models\BreachEvent;
use Illuminate\Support\Carbon;
use App\Constants\AppConstants;
use Illuminate\Console\Command;
use App\Constants\EmailConstants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SendGrid\SendMailService;

class BreachedUserReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:breach-reminder-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description: User breached but didnt repurhcased or renewed any plan';

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
     * @return int
     */
    public function handle()
    {
        $allBreached = BreachEvent::whereHas('account', function ($q) {
            $q->where('breached', 1)
                ->where('breachedBy', 'LIKE', '%Loss%');
        })->where(function ($q) {
            $q->orWhereDate('created_at', Carbon::now()->subDays(1));
            $q->orWhereDate('created_at', Carbon::now()->subDays(7));
            $q->orWhereDate('created_at', Carbon::now()->subDays(30));
            $q->orWhereDate('created_at', Carbon::now()->subDays(90));
        })->get();
        $accountsId = [];
        foreach ($allBreached as $breached) {
            Log::info($breached->account_id);
            $accountsId[] = $breached->account_id;
        }
        $customers = Customer::whereHas('accounts', function ($q) use ($accountsId) {
            $q->whereIn('id', $accountsId);
        })->get();

        foreach ($customers as $customer) {
            $accountLatestBreached=Account::with('customer')->where('customer_id', $customer->id)->where('breached',1)->first();
            $details = [
                'template_id'          => EmailConstants::REMINDER_MAIL_USER_BREACHED_BUT_NOT_PURCHASED,
                'to_name'              => $customer->name,
                'to_email'             => $customer->email,
                'email_body' => [
                    "name" => $customer->name,
                    "login_id" => $accountLatestBreached,
                    "discount_code" => env("COUPON_DISCOUNT"),
                ]
            ];
            EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
        }
    }
}
