<?php

namespace App\DataSource;

class OrderData
{
    /**
     * @var int|null account id from accounts table
     */
    public int|null $accountId;

    /**
     * @var int|null customer id from customer table
     */
    public int|null $customerId;

    /**
     * @var string customer email
     */
    public string $email;

    /**
     * @var int order type should come from AppConstants::class
     */
    public int $orderType;

    /**
     * @var int gateway type should come from AppConstants::class
     */
    public int $gateway;

    /**
     * @var float total amount
     */
    public float $total;

    /**
     * @var float discount amount
     */
    public float $discount;

    /**
     * @var float grand total amount
     */
    public float $gradTotal;

    /**
     * @var string transaction id
     */
    public string|null $transactionId;

    /**
     * @var string|null coupon id from coupons table
     */
    public string|null $couponId;

    /**
     * @var int|null parent order id
     */
    public int|null $parentOrderId;

    /**
     * @var int
     */
    public int $status;

    /**
     * @var int|null
     */
    public int|null $jlPlanId;

    /**
     * @var string|null
     */
    public string|null $remarks;
    public string|array|null $billing_address;

    /**
     * @var string server name
     */
    public string|null $serverName;
}
