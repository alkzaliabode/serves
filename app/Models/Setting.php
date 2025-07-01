<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // اسم الجدول المرتبط بالنموذج
    protected $table = 'settings';

    // الأعمدة التي يمكن تعبئتها جماعياً
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * جلب قيمة إعداد معين بناءً على المفتاح.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // ابحث عن الإعداد بواسطة المفتاح وارجع قيمته، أو القيمة الافتراضية إذا لم يتم العثور عليه
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * تعيين أو تحديث قيمة إعداد معين.
     *
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting
     */
    public static function set(string $key, $value)
    {
        // ابحث عن الإعداد، أو قم بإنشاء واحد جديد إذا لم يكن موجوداً
        return self::updateOrCreate(
            ['key' => $key], // البحث بواسطة المفتاح
            ['value' => $value] // تحديث القيمة أو تعيينها عند الإنشاء
        );
    }
}

