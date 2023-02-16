<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Settings extends Model
{
    use HasFactory;

    public const GLOBAL_TYPE = 'global';

    public $fillable = [
        'settings',
    ];

    public static function getGlobalRefreshToken(): ?string
    {
        $settings = Settings::where('type', '=', Settings::GLOBAL_TYPE)->first();
        $decodedSettings = $settings ? json_decode($settings->settings, true) : [];

        return $decodedSettings['refreshToken'] ?? null;
    }

}
