<?php

namespace App\DTO;

class Plan extends DataTransferObject
{
    public int $setup_fee;
    public int $quantity;
    public string $tax_name;
    public string $setup_fee_tax_percentage;
    public string $plan_code;
    public string $description;
    public string $discount;
    public array $item_custom_fields = [];
    public string $tax_id;
    public array $tags = [];
    public string $setup_fee_tax_id;
    public int $total;
    public string $unit;
    public string $setup_fee_tax_name;
    public string $tax_type;
    public string $price;
    public string $name;
    public int $tax_percentage;
    public string $setup_fee_tax_type;
    public string $pricing_scheme;
    public string $plan_id;

}
