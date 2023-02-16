<?php

namespace App\DTO;

use DateTime;

class SubscriptionPreview extends DataTransferObject
{
    public string $customer_id;
    public string $customer_name;
    public string $email;
    public string $plan_name;
    public string $plan_code;
    public string $subscription_id;
    public string $name;
    public string $crm_owner_id;
    public string $zcrm_potential_id;
    public string $zcrm_potential_name;
    public string $subscription_number;
    public string $is_metered_billing;
    public string $status;
    public string $sub_total;
    public string $amount;
    public DateTime $created_at;
    public ?DateTime $activated_at = null;
    public ?DateTime $current_term_starts_at = null;
    public ?DateTime $current_term_ends_at = null;
    public ?DateTime $last_billing_at = null;
    public ?DateTime $next_billing_at = null;
    public ?DateTime $expires_at = null;
    public int $interval;
    public string $interval_unit;
    public int $shipping_interval;
    public string $shipping_interval_unit;
    public string $billing_mode;
    public ?DateTime $next_shipment_at = null;
    public ?DateTime $next_shipment_day = null;
    public string $total_orders;
    public string $orders_created;
    public string $orders_remaining;
    public ?DateTime $last_shipment_at = null;
    public ?DateTime $last_shipment_day = null;
    public bool $auto_collect;
    public DateTime $created_time;
    public DateTime $updated_time;
    public string $reference_id;
    public string $salesperson_id;
    public string $salesperson_name;
    public string $currency_code;
    public string $currency_symbol;
    public string $coupon_duration;
    public ?DateTime $scheduled_cancellation_date = null;
    public array $custom_fields = [];
    public int $payment_terms;
    public string $payment_terms_label;
    public string $created_by;
}
