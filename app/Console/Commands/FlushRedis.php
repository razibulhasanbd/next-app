<?php

namespace App\Console\Commands;

use App\Constants\CommandConstants;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class FlushRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Flush_Redis;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Redis flush';

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
        // Redis::command('flushdb');
        return 0;
    }
}
