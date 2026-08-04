<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shop_info_p';

    protected $fillable = [
        'shop_name',
        'logo',
        'address',
        'hotline',
        'zalo',
        'email',
        'fanpage',
    ];

    public function getShopInfo()
    {
        $shop_info = $this->first();

        return $shop_info;

    }

    public static function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // Chỉ giữ số

        if (strlen($phone) == 10) {
            return substr($phone, 0, 4).' '.
                substr($phone, 4, 3).' '.
                substr($phone, 7, 3);
        }

        return $phone;
    }
}
