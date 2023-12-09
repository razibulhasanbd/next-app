<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

use App\Models\Account;
use App\Services\AccountService;
use App\Services\RuleBreachService;
use App\Repository\AccountRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RuleBreachTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected $accountService;



    protected function setUp(): void
    {
        parent::setUp();
        $this->accountService = new AccountService();
    }




    public function is_fetching_accounts()
    {


        $accountRepo = new AccountRepository();
        $notBreachedAccounts = $accountRepo->notBreachedAccounts();

        $this->assertGreaterThan(1, $notBreachedAccounts->count());
    }

    public function is_creating_yesterday_metric()
    {


        $account = Account::latest()->first();

        $attributes = [
            'server' => $account->server,
            'url' => $account->server->url,
            'sessionToken' => $account->server->login,
            'login' => $account->login,
            'id' => $account->id,
        ];

        $this->accountService->createYesterdayMetric($attributes);


        $yesterdayMetric = $account->lastDayMetric;
        $this->assertNotNull($yesterdayMetric);
    }

    public function is_creating_today_metric()
    {

        $account = Account::latest()->first();

        $attributes = [
            'server' => $account->server,
            'url' => $account->server->url,
            'sessionToken' => $account->server->login,
            'login' => $account->login,
            'id' => $account->id,
        ];
        $margin= $this->accountService->margin($attributes);

        $this->accountService->createTodayMetric($account,$margin);


        $todayMetric = $account->todayMetric;
        $this->assertNotNull($todayMetric);
    }


    public function it_tests_if_account_breached_daily_loss_limit_rule()
    {
        $account = Account::latest()->first();
        $planRules = $account->planRules();

        $this->accountService->checkDailyLossLimitRule($account, $planRules);


        $this->assertTrue($account->breachedDailyLossLimitRule);
    }

    /** @test */
    public function it_tests_if_both_metrics_are_created_if_doesnt_exist()
    {
        $account = Account::latest()->first();

        $marginAttributes = [
            'currentBalance' => $account->starting_balance,
            'currentEquity' => $account->starting_balance,
        ];
        // $accountService=new AccountService();
        $mockAccountService = $this->getMockBuilder(AccountService::class)->onlyMethods(['margin'])->getMock();
        $mockAccountService->method('margin')
            ->will($this->returnValue($marginAttributes));

        $ruleBreachService = new RuleBreachService($mockAccountService);
        $margin = $mockAccountService->margin($account);

        $ruleBreachService->check($account, $margin);
        $account->refresh();
        $this->assertNotNull($account->todayMetric);
        $this->assertNotNull($account->lastDayMetric);
    }

    /** @test */
    public function it_tests_if_today_metric_is_created_if_doesnt_exist()
    {
        $account = Account::latest()->first();

        $marginAttributes = [
            'currentBalance' => $account->starting_balance,
            'currentEquity' => $account->starting_balance,
        ];
        // $accountService=new AccountService();
        $mockAccountService = $this->getMockBuilder(AccountService::class)->onlyMethods(['margin'])->getMock();
        $mockAccountService->method('margin')
            ->will($this->returnValue($marginAttributes));

        $ruleBreachService = new RuleBreachService($mockAccountService);

        $margin =  $mockAccountService->margin($account);

        $ruleBreachService->check($account, $margin);
        $account->refresh();
        $this->assertNotNull($account->todayMetric);
    }



    // protected function tearDown(): void
    // {
    //     Artisan::call('migrate:reset');
    //     parent::tearDown();
    // }

}
