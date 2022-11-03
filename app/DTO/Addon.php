<?php

namespace App\DTO;

use App\Models\Video;

class Addon extends DataTransferObject
{
    public int $quantity;
    public string $tax_name;
    public string $description;
    public string $discount;
    public array $item_custom_fields = [];
    public string $tax_id;
    public array $tags = [];
    public string $addon_id;
    public string $unit;
    public string $total;
    public string $tax_type;
    public string $price;
    public string $name;
    public string $tax_percentage;
    public string $addon_code;
    public string $pricing_scheme;
    public ?Video $video = null;

}
