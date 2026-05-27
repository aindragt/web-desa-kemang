<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'kunci',
        'nilai',
    ];

    /**
     * Helper static method to quickly retrieve a setting value.
     */
    public static function getValue(string $key, $default = null): ?string
    {
        $setting = self::where('kunci', $key)->first();
        
        return $setting ? $setting->nilai : $default;
    }
}
