<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li>
            <select class="searchable-field form-control">

            </select>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.home') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/permissions*') ? 'c-show' : '' }} {{ request()->is('admin/roles*') ? 'c-show' : '' }} {{ request()->is('admin/users*') ? 'c-show' : '' }} {{ request()->is('admin/audit-logs*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.permissions.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.roles.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.users.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.audit-logs.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('customer_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.customers.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/customers') || request()->is('admin/customers/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-user-circle c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.customer.title') }}
                </a>
            </li>
        @endcan
        @can('account_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.accounts.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/accounts') || request()->is('admin/accounts/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-user-alt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.account.title') }}
                </a>
            </li>

            @can('product_and_order_system_access')
                <li class="c-sidebar-nav-dropdown {{ request()->is("admin/business-models*") ? "c-show" : "" }} {{ request()->is("admin/model-varients*") ? "c-show" : "" }} {{ request()->is("admin/products*") ? "c-show" : "" }} {{ request()->is("admin/product-details*") ? "c-show" : "" }} {{ request()->is("admin/product-labels*") ? "c-show" : "" }} {{ request()->is("admin/coupons*") ? "c-show" : "" }}">
                    <a class="c-sidebar-nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.productAndOrderSystem.title') }}
                    </a>
                    <ul class="c-sidebar-nav-dropdown-items">
                        @can('business_model_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.business-models.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/business-models") || request()->is("admin/business-models/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.businessModel.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('model_varient_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.model-varients.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/model-varients") || request()->is("admin/model-varients/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.modelVarient.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('product_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.product.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('product_detail_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.product-details.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-details") || request()->is("admin/product-details/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.productDetail.title') }}
                                </a>
                            </li>
                        @endcan

                        @can('product_label_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.product-labels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-labels") || request()->is("admin/product-labels/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.productLabel.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('coupon_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.coupons.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/coupons") || request()->is("admin/coupons/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.coupon.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.accounts.breachEvent') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/accounts') || request()->is('admin/accounts/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-user-alt c-sidebar-nav-icon">

                    </i>
                    Breach Event
                </a>
            </li>

            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.accounts.topUpLog') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/accounts') || request()->is('admin/accounts/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-user-alt c-sidebar-nav-icon">

                    </i>
                    TopUp View
                </a>
            </li>

            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.accounts.accountProfit') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/accounts') || request()->is('admin/accounts/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-money-bill-alt c-sidebar-nav-icon">

                    </i>
                    Account Profit View
                </a>
            </li>

            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.accounts.accountsByDateRange') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/accounts') || request()->is('admin/accounts/*') ? 'c-active' : '' }}">
                    <i class="fa fas fa-life-ring c-sidebar-nav-icon">

                    </i>
                    Accounts By Date Range
                </a>
            </li>
        @endcan
        @can('account_metric_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.account-metrics.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/account-metrics') || request()->is('admin/account-metrics/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.accountMetric.title') }}
                </a>
            </li>
        @endcan
        @can('plan_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.plans.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/plans') || request()->is('admin/plans/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-spa c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.plan.title') }}
                </a>
            </li>
        @endcan

        @can('coupon_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.coupons.index') }}"
                    class="c-sidebar-nav-link {{ request()->is('admin/coupons') || request()->is('admin/coupons/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.coupon.title') }}
                </a>
            </li>
        @endcan

        @can('order_management_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/orders*') ? 'c-show' : '' }} {{ request()->is('admin/orders*') ? 'c-show' : '' }} {{ request()->is('admin/orders*') ? 'c-show' : '' }} {{ request()->is('admin/orders*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-shopping-cart mr-3">

                    </i>
                    Order Management
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    {{-- @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.permissions.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan --}}
                    @can('order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.orders.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-list c-sidebar-nav-icon">

                                </i>
                                Orders List
                            </a>
                        </li>
                    @endcan

                    @can('order_create')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.orders.create') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-plus c-sidebar-nav-icon">

                                </i>
                                Create account order
                            </a>
                        </li>
                    @endcan

                    @can('refund_list')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.refunds.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/refunds') || request()->is('admin/refunds/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-plus c-sidebar-nav-icon">

                                </i>
                                Refund List
                            </a>
                        </li>
                    @endcan


                    @can('charge_list')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.charges.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/charges') || request()->is('admin/charges/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-plus c-sidebar-nav-icon">

                                </i>
                                Add Charge List
                            </a>
                        </li>
                    @endcan



                </ul>
            </li>
        @endcan


        @can('analytics')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/analytics*') ? 'c-show' : '' }} {{ request()->is('admin/analytics*') ? 'c-show' : '' }} {{ request()->is('admin/analytics*') ? 'c-show' : '' }} {{ request()->is('admin/analytics*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart mr-3 fa-fw" aria-hidden="true"></i>
                    Analytics
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('analytics_daily_reports')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.analytics.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/orders') || request()->is('admin/analytics/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fa fa-pie-chart c-sidebar-nav-icon" aria-hidden="true"></i>
                                Daily sales reports
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('subscription_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.subscriptions.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/subscriptions') || request()->is('admin/subscriptions/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-subscript c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.subscription.title') }}
                </a>
            </li>
        @endcan

        @can('trade_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/trades*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.trade.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.trades.index') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/trades') || request()->is('admin/trades/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fab fa-trade-federation c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.trade.title') }} {{ trans('global.list') }}
                        </a>
                    </li>

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.trades.openTrade') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/trades') || request()->is('admin/trades/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.trade.open_trade') }}
                        </a>
                    </li>


                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.trades.closeTrade') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/trades') || request()->is('admin/trades/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.trade.close_trade') }}
                        </a>
                    </li>

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.trades.arbitraryTrade') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/trades') || request()->is('admin/trades/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                            </i>
                            Arbitrary Trades
                        </a>
                    </li>

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.trades.arbitraryTradeReport') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/trades') || request()->is('admin/trades/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                            </i>
                            Arbitrary Trades Report
                        </a>
                    </li>

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.trades.EATrades') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/trades') || request()->is('admin/trades/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                            </i>
                            EA Trades
                        </a>
                    </li>
                    @can('trade_sl_tp_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.trade-sl-tps.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/trade-sl-tps') || request()->is('admin/trade-sl-tps/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.tradeSlTp.title') }}
                            </a>
                        </li>
                    @endcan

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('admin.trades.tradesByDateRange') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/trades') || request()->is('admin/trades/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fab fa-trade-federation c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.trade.title') }} {{ trans('global.list') }} By Date Range
                        </a>
                    </li>
                </ul>
            </li>
        @endcan


        @can('package_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.packages.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/packages') || request()->is('admin/packages/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-cubes c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.package.title') }}
                </a>
            </li>
        @endcan
        @can('mt_server_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.mt-servers.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/mt-servers') || request()->is('admin/mt-servers/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.mtServer.title') }}
                </a>
            </li>
        @endcan
        @can('rule_name_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.rule-names.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/rule-names') || request()->is('admin/rule-names/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-pencil-ruler c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.ruleName.title') }}
                </a>
            </li>
        @endcan
        @can('plan_rule_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.plan-rules.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/plan-rules') || request()->is('admin/plan-rules/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fab fa-first-order-alt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.planRule.title') }}
                </a>
            </li>
        @endcan

        @can('growth_fund_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.growth-funds.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/growth-funds') || request()->is('admin/growth-funds/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-signal c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.growthFund.title') }}
                </a>
            </li>
        @endcan

        @can('retake_access')
            {{-- <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.retakes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/retakes") || request()->is("admin/retakes/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-undo-alt c-sidebar-nav-icon">

                </i>
                {{ trans('cruds.retake.title') }}
            </a>
        </li> --}}


            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/retakes*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.retake.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route('retakeRequestList') }}"
                           class="c-sidebar-nav-link {{ request()->is('admin/retakes') || request()->is('admin/retakes/*') ? 'c-active' : '' }}">
                            <i class="fa-fw fab fa-trade-federation c-sidebar-nav-icon">

                            </i>
                            Retake Requests
                        </a>
                    </li>


                    {{-- <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.trades.openTrade") }}" class="c-sidebar-nav-link {{ request()->is("admin/trades") || request()->is("admin/trades/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.trade.open_trade') }}
                        </a>
                    </li> --}}

                </ul>
            </li>

            @can('cycle_extension_access')
                <li class="c-sidebar-nav-dropdown {{ request()->is('admin/extend-cycle-logs*') ? 'c-show' : '' }}">
                    <a class="c-sidebar-nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-space-shuttle c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.cycleExtension.title') }}
                    </a>
                    <ul class="c-sidebar-nav-dropdown-items">
                        @can('extend_cycle_log_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route('admin.extend-cycle-logs.index') }}"
                                   class="c-sidebar-nav-link {{ request()->is('admin/extend-cycle-logs') || request()->is('admin/extend-cycle-logs/*') ? 'c-active' : '' }}">
                                    <i class="fa-fw fas fa-history c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.extendCycleLog.title') }}
                                </a>
                            </li>
                        @endcan

                        @can('extend_cycle_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route('admin.extend-cycle.index') }}"
                                   class="c-sidebar-nav-link {{ request()->is('admin/extend-cycle-logs') || request()->is('admin/extend-cycle-logs/*') ? 'c-active' : '' }}">
                                    <i class="fa-fw fas fa-history c-sidebar-nav-icon">

                                    </i>
                                    Extend Cycle
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan




        @endcan

        @can('target_reached_account_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.target-reached-accounts.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/target-reached-accounts') || request()->is('admin/target-reached-accounts/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-external-link-square-alt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.targetReachedAccount.title') }}
                </a>
            </li>
        @endcan

        @can('outside_payment_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/typeforms*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-amazon-pay c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.outsidePayment.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('typeform_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.typeforms.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/typeforms') || request()->is('admin/typeforms/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-medrt c-sidebar-nav-icon">

                                </i>
                                Payments
                            </a>
                        </li>
                    @endcan
                </ul>

                <ul class="c-sidebar-nav-dropdown-items">
                    @can('typeform_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.typeforms.archivedPayments') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/typeforms') || request()->is('admin/typeforms/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-medrt c-sidebar-nav-icon">

                                </i>
                                Archived Payments
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('fn_certificate_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/certificate-types*') ? 'c-show' : '' }} {{ request()->is('admin/ceritificates*') ? 'c-show' : '' }} {{ request()->is('admin/account-certificates*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-certificate c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.fnCertificate.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('certificate_type_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.certificate-types.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/certificate-types') || request()->is('admin/certificate-types/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-centercode c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.certificateType.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ceritificate_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.ceritificates.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/ceritificates') || request()->is('admin/ceritificates/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-crown c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ceritificate.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('account_certificate_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.account-certificates.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/account-certificates') || request()->is('admin/account-certificates/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-star c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.accountCertificate.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('account_rule_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.account-rules.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/account-rules') || request()->is('admin/account-rules/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-ruler-combined c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.accountRule.title') }}
                </a>
            </li>
        @endcan

        @can('account_rule_template_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.account-rule-templates.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/account-rule-templates') || request()->is('admin/account-rule-templates/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-check-double c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.accountRuleTemplate.title') }}
                </a>
            </li>
        @endcan

        @can('approval_category_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.approval-categories.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/approval-categories') || request()->is('admin/approval-categories/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-clipboard-check c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.approvalCategory.title') }}
                </a>
            </li>
        @endcan


        @can('announcement_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.announcements.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/announcements') || request()->is('admin/announcements/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-bullhorn c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.announcement.title') }}
                </a>
            </li>
        @endcan

        @can('account_rule_template_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.account-rule-templates.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/account-rule-templates') || request()->is('admin/account-rule-templates/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-check-double c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.accountRuleTemplate.title') }}
                </a>
            </li>
        @endcan
        @can('approval_category_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.approval-categories.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/approval-categories') || request()->is('admin/approval-categories/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-clipboard-check c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.approvalCategory.title') }}
                </a>
            </li>
        @endcan
        @can('faq_management_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/categories*') ? 'c-show' : '' }} {{ request()->is('admin/types*') ? 'c-show' : '' }} {{ request()->is('admin/tags*') ? 'c-show' : '' }} {{ request()->is('admin/sections*') ? 'c-show' : '' }} {{ request()->is('admin/questions*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-question c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.faqManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.categories.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/categories') || request()->is('admin/categories/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-chess-board c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.category.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('type_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.types.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/types') || request()->is('admin/types/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fab fa-typo3 c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.type.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('tag_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.tags.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/tags') || request()->is('admin/tags/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-tags c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.tag.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('section_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.sections.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/sections') || request()->is('admin/sections/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-puzzle-piece c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.section.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('question_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.questions.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/questions') || request()->is('admin/questions/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-question-circle c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.question.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('master_data_access')
            <li
                class="c-sidebar-nav-dropdown {{ request()->is('admin/account-statuses*') ? 'c-show' : '' }} {{ request()->is('admin/account-status-messages*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-home c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.masterData.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('account_status_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.account-statuses.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/account-statuses') || request()->is('admin/account-statuses/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-fire c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.accountStatus.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('account_status_message_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.account-status-messages.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/account-status-messages') || request()->is('admin/account-status-messages/*') ? 'c-active' : '' }}">
                                <i class="fa-fw far fa-comment c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.accountStatusMessage.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('account_status_log_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.account-status-logs.index') }}"
                    class="c-sidebar-nav-link {{ request()->is('admin/account-status-logs') || request()->is('admin/account-status-logs/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-recycle c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.accountStatusLog.title') }}
                </a>
            </li>
        @endcan



        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.systemCalendar') }}"
               class="c-sidebar-nav-link {{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-calendar">

                </i>
                {{ trans('global.systemCalendar') }}
            </a>
        </li>
        @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}"
                       href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        @can('trader_game_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.trader-games.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/trader-games') || request()->is('admin/trader-games/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-gamepad c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.traderGame.title') }}
                </a>
            </li>
        @endcan

        @can('news_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/news-calendars*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-bullhorn c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.newsCalendar.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('news_calendar_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.news-calendars.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/news-calendars') || request()->is('admin/news-calendars/*') ? 'c-active' : '' }}">
                                <i class="fa-fw far fa-calendar-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.newsCalendar.get_all_news') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('utility_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/utility-categories*') ? 'c-show' : '' }} {{ request()->is('admin/utility-items*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.utility.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('utility_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.utility-categories.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/utility-categories') || request()->is('admin/utility-categories/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.utilityCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('utility_item_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.utility-items.index') }}"
                                class="c-sidebar-nav-link {{ request()->is('admin/utility-items') || request()->is('admin/utility-items/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                            </i>
                            {{ trans('cruds.utilityItem.title') }}
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan
        @can('download_manager')
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.download-manager.index') }}"
                   class="c-sidebar-nav-link {{ request()->is('admin/download-manager') || request()->is('admin/download-manager/*') ? 'c-active' : '' }}">
                    <i class="fa-fw fas fa-download c-sidebar-nav-icon">

                    </i>
                    {{ trans('global.download_manager') }}
                </a>
            </li>
        @endcan
        @can('payment_setting_menu')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/payment-method*') ? 'c-show' : '' }} {{ request()->is('admin/analytics*') ? 'c-show' : '' }} {{ request()->is('admin/analytics*') ? 'c-show' : '' }} {{ request()->is('admin/analytics*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart mr-3 fa-fw" aria-hidden="true"></i>
                    {{ trans('global.payment_setting') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('payment_method_list')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.payment-method.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/payment-method') || request()->is('admin/payment-method*') ? 'c-active' : '' }}">
                                <i class="fa-fw fa fa-pie-chart c-sidebar-nav-icon" aria-hidden="true"></i>
                                {{ trans('global.payment_method_list') }}
                            </a>
                        </li>
                    @endcan
                    @can('payment_method_review_list')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.payment-method-review.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/payment-method-review') || request()->is('admin/payment-method-review*') ? 'c-active' : '' }}">
                                <i class="fa-fw fa fa-list c-sidebar-nav-icon" aria-hidden="true"></i>
                                {{ trans('global.payment_method_list_waiting_for_approval') }}
                            </a>
                        </li>
                    @endcan
                    @can('payment_method_country_list')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.payment_method.country_list.index') }}"
                               class="c-sidebar-nav-link {{ request()->is('admin/country-list') || request()->is('admin/country-list*') ? 'c-active' : '' }}">
                                <i class="fa-fw fa fa-flag c-sidebar-nav-icon" aria-hidden="true"></i>
                                {{ trans('global.country_list') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link"
               onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
