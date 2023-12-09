<?php

namespace App\Constants;

class CommandConstants{


    public const Breached_Account_Trade_Close          = "trade:close-breached-account";
    public const Custom_TradeSync                      = "trade:custom-sync {--accountId=*} {--fromHour=34} {--toHour=24}";
    public const News_Calendar                         = "news:calendar";
    public const Origin_Account_Data_Migration_Command = "data-migration:origin-account";
    public const Rule_Breach_Check                     = "rulebreach:short";
    public const Server_Ping                           = "ping:short";
    public const Weekly_Bulk_Trade_Close               = "trade:close-weekly";
    public const Flush_Redis                           = "flush:redis";
    public const Check_News_Trade                      = "news-trade-check";
    public const News_Calendar_TimeZone_Change         = "news:calendar-timezone-change";
    public const ScheduleJobs_Mtinit                   = "scheduleJobs:mt-init";
    public const ScheduleJobs_ProfitChecker            = "scheduleJobs:profitChecker";
    public const ScheduleJobs_TradeSyncJob             = "scheduleJobs:tradeSyncJob";
    public const PlanUpdateDuration                    = "plan:update-duration {duration}";
    public const Pending_Orders_CrossCheck_Command     = "orders:pending-order-cross-check";
    public const Breach_reminder_email                 = "user:breach-reminder-email";
    public const News_Calendar_Push_Notification_To_All = "news_calendar_push_notification:send_to_all";


}
