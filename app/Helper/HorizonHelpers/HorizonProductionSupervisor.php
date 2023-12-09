<?php

namespace App\Helper\HorizonHelpers;

use App\Constants\AppConstants;

class HorizonProductionSupervisor
{
    /**
     * get all production supervisors
     *
     * @return array
     */
    public static function get() : array{
        $supervisors = [];
        if (config('const.SUPERVISOR_QUEUE_BREACH_EVENT_JOB_ENABLED')) {
            $supervisors['supervisor_' . AppConstants::QUEUE_BREACH_EVENT_JOB] = [
                'connection' => 'redis-long-running',
                'queue' => [
                    AppConstants::QUEUE_BREACH_EVENT_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_BREACH_EVENT_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_BREACH_EVENT_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 1000,
                'timeout'      => 120                                                                //2 minutes
            ];
        }

        if (config('const.SUPERVISOR_QUEUE_ON_BREACH_NOTIFY_USER_JOB_ENABLED')) {
            $supervisors['supervisor_' . AppConstants::QUEUE_ON_BREACH_NOTIFY_USER_JOB] = [
                'connection' => 'redis-long-running',
                'queue' => [
                    AppConstants::QUEUE_ON_BREACH_NOTIFY_USER_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_ON_BREACH_NOTIFY_USER_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_ON_BREACH_NOTIFY_USER_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 500,
                'timeout'      => 80                                                                //1 minutes 20 seconds
            ];
        }

        if (config('const.SUPERVISOR_QUEUE_DEFAULT_JOB_ENABLED')) {
            $supervisors['supervisor_' . AppConstants::QUEUE_DEFAULT_JOB] = [
                'connection' => 'redis',
                'queue' => [
                    AppConstants::QUEUE_DEFAULT_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_DEFAULT_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_DEFAULT_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 1000,
                'timeout'      => 120                                                                //2 minutes
            ];
        }

        if (config('const.SUPERVISOR_QUEUE_TRADE_SYNC_JOB_ENABLED')) {
            $supervisors['supervisor_' . AppConstants::QUEUE_TRADE_SYNC_JOB] = [
                'connection' => 'redis',
                'queue' => [
                    AppConstants::QUEUE_TRADE_SYNC_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_TRADE_SYNC_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_TRADE_SYNC_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 1000,
                'timeout'      => 600                                                                //10 minutes
            ];
        }

        if (config('const.SUPERVISOR_QUEUE_TRADE_CLOSE_EVENT_JOB_ENABLED')) {
            $supervisors['supervisor_' . AppConstants::QUEUE_TRADE_CLOSE_EVENT_JOB] = [
                'connection' => 'redis-long-running',
                'queue' => [
                    AppConstants::QUEUE_TRADE_CLOSE_EVENT_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_TRADE_CLOSE_EVENT_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_TRADE_CLOSE_EVENT_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 1000,
                'timeout'      => 600                                                                //10 minutes
            ];
        }

        if (config('const.SUPERVISOR_QUEUE_TRADE_CLOSE_JOB_ENABLED')) {
            $supervisors['supervisor_' . AppConstants::QUEUE_TRADE_CLOSE_JOB] = [
                'connection' => 'redis',
                'queue' => [
                    AppConstants::QUEUE_TRADE_CLOSE_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_TRADE_CLOSE_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_TRADE_CLOSE_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 1000,
                'timeout'      => 300                                                                //05 minutes
            ];
        }

        if (config('const.SUPERVISOR_QUEUE_PROFIT_CHECKER_JOB_ENABLED')) {
            $supervisors['supervisor_' . AppConstants::QUEUE_PROFIT_CHECKER_JOB] = [
                'connection' => 'redis',
                'queue' => [
                    AppConstants::QUEUE_PROFIT_CHECKER_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_PROFIT_CHECKER_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_PROFIT_CHECKER_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 1000,
                'timeout'      => 300                                                                //05 minutes
            ];
        }

        if (config('const.SUPERVISOR_QUEUE_DISCORD_ALERT_JOB_ENABLED')) {
            $supervisors['supervisor_'.AppConstants::QUEUE_DISCORD_ALERT_JOB] = [
                'connection' => 'redis',
                'queue' => [
                    AppConstants::QUEUE_DISCORD_ALERT_JOB,
                ],
                'balance'      => 'auto',
                'minProcesses' => config('const.SUPERVISOR_QUEUE_DISCORD_ALERT_JOB_MIN_PROCESS', 1),
                'maxProcesses' => config('const.SUPERVISOR_QUEUE_DISCORD_ALERT_JOB_MAX_PROCESS', 3),
                'tries'        => AppConstants::JOB_TRIES,
                'memory'       => 1000,
                'timeout'      => 600                                                                //10 minutes
            ];
        }

        return $supervisors;
    }
}
