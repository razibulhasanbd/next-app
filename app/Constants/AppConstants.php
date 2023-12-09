<?php

namespace App\Constants;

class AppConstants {

    // environment
    public const ENVIRONMENT_LOCAL      = 'local';
    public const ENVIRONMENT_DEV        = 'dev';
    public const ENVIRONMENT_PRODUCTION = 'production';

    // database connection name
    public const DATABASE_CONNECTION_FUNDED_NEXT_BACKEND = "jl_backend_mysql";

    // Queues
    public const QUEUE_BREACH_EVENT_JOB          = 'breach_event';
    public const QUEUE_ON_BREACH_NOTIFY_USER_JOB = 'on_breach_notify_user';
    public const QUEUE_DEFAULT_JOB               = 'default';
    public const QUEUE_TRADE_SYNC_JOB            = 'trade_sync';
    public const QUEUE_TRADE_CLOSE_EVENT_JOB     = 'trade_close_event';
    public const QUEUE_TRADE_CLOSE_JOB           = 'trade_close';
    public const QUEUE_PROFIT_CHECKER_JOB        = 'profit_checker';
    public const QUEUE_DISCORD_ALERT_JOB         = 'discord_alert';

    public const REDIS_ACCOUNT_METRIC = "redis_account_metric_temporary";

    // Cache
    public const CACHE_KEY_UTILITY_ITEMS      = 'cache_utility_items';
    public const CACHE_KEY_UTILITY_CATEGORIES = 'cache_utility_categories';

    // Job
    public const JOB_TRIES = 3;

    // Horizon
    public const HORIZON_AUTH_USERNAME_CONFIG_KEY = 'horizon.auth.username';
    public const HORIZON_AUTH_PASSWORD_CONFIG_KEY = 'horizon.auth.password';

    //Cycle duration for evaluation real accounts
    public const EV_REAL_FIRST_CYCLE_15_DAYS = 15;

    // starting balance for real nca account
    public const NCA_STARTING_BALANCE_FOR_REAL_ACCOUNT = 0.25;

    // product order type
    public const PRODUCT_ORDER_NEW_ACCOUNT  = 1;
    public const PRODUCT_ORDER_TOPUP        = 2;
    public const PRODUCT_ORDER_RESET        = 3;
    public const PRODUCT_ORDER_INVOICE      = "product_order_invoice";

    // coupon type
    public const COUPON_FLAT                = 1;
    public const COUPON_PERCENTAGE          = 2;

    // gateways
    public const GATEWAY_CHECKOUT = 1;
    public const GATEWAY_OUTSIDE = 2;
    public const GATEWAY_STRIPE = 3;

    public const PASSED = 1;
    public const NOT_PASSED = 0;
    public const ON_GOING = 2;

    public const TRADING_STATS = 'stats';
    public const TRADING_ANALYTICS = 'analytics';
    public const TRADING_SYMBOL = 'symbol-performance';
    public const WEEKLY_PROFIT_LOSS = 'weekly-profit-loss';
    public const HOURLY_PROFIT_LOSS = 'hourly-profit-loss';
    public const AVERAGE_PROFIT_LOSS_PERCENTAGE = 'average-profit-loss';
    public const BY_SEL_ORDER_TYPE = 'buy-sell-order-type';
    public const buy = 'buy';
    public const sell = 'sell';
    public const FREE_ACCOUNT = 4;
    public const KYC_APPROVED = 'approved';
    public const EXPRESS_ELIGIBLE_PERCENTAGE = 25;

    // trading servers type
    public const TRADING_SERVER_MT4 = "mt4";
    public const TRADING_SERVER_MT5 = "mt5";

    public const ONE_SIGNAL_PUSH_SINGLE_USER = 'SINGLE_USER';
    public const ONE_SIGNAL_PUSH_SINGLE_USER_CUSTOM = 'SINGLE_USER_CUSTOM';
}
