<?php

namespace App\DTO;

use DateTime;

class Subscription extends DataTransferObject
{
    public string $invoice_notes;
    public DateTime $next_shipment_day;
    public DateTime $updated_time;
    public array $notes = [];
    public string $zcrm_potential_id;
    public string $reference_id;
    public int $trial_extended_count;
    public DateTime $next_billing_at;
    public array $taxes = [];
    public string $coupon_duration;
    public string $subscription_id;
    public string $last_modified_by_id;
    public bool $end_of_term;
    public string $product_id;
    public string $pricebook_id;
    public string $created_time;
    public string $shipping_interval_unit;
    public string $exchange_rate;
    public bool $is_inclusive_tax;
    public int $orders_remaining;
    public array $custom_fields = [];
    public string $last_shipment_at;
    public array $auto_apply_credits = [];
    public string $product_name;
    public DateTime $activated_at;
    public bool $is_metered_billing;
    public string $name;
    public string $zcrm_potential_name;
    public int $interval;
    public string $created_by_id;
    public string $crm_owner_id;
    public string $status;
    public array $items_associated = [];
    public string $billing_mode;
    public string $channel_reference_id;
    public DateTime $created_at;
    public int $shipping_interval;
    public int $payment_terms;
    public DateTime $last_billing_at;
    public string $currency_code;
    public bool $can_prorate;
    public string $scheduled_cancellation_date;
    public ?DateTime $expires_at;
    public string $interval_unit;
    public DateTime $end_of_term_scheduled_date;
    public bool $can_add_bank_account;
    public bool $is_advance_invoice_present;
    public ?DateTime $last_shipment_day;
    public string $tax_rounding;
    public DateTime $start_date;
    public DateTime $next_shipment_at;
    public array $payment_gateways = [];
    public int $amount;
    public int $remaining_billing_cycles;
    public string $subscription_number;
    public string $currency_symbol;
    public DateTime $current_term_starts_at;
    public DateTime $current_term_ends_at;
    public int $total_orders;
    public string $salesperson_name;
//    /** @var  App\DTO\Addon [] */
//    public array $pending_addons = [];
    public string $salesperson_id;
    public string $child_invoice_id;
    public bool $auto_collect;
    public string $channel_source;
    public string $sub_total;
    public DateTime $created_date;
    public bool $allow_partial_payment;
    public int $orders_created;
    public string $customer_id;
    public string $payment_terms_label;

    //it is very important to specify full path with space before []
    /** @var  App\DTO\Addon [] */
    public array $addons = [];
    public Plan $plan;

}
