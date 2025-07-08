<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // إنشاء جدول جديد يسمى 'settings'
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); // عمود المعرف الأساسي التلقائي
            $table->string('key')->unique(); // مفتاح الإعداد (مثل 'background_image_url') - يجب أن يكون فريداً
            $table->text('value')->nullable(); // قيمة الإعداد (مثل مسار الصورة) - يمكن أن تكون فارغة
            $table->timestamps(); // عمودين 'created_at' و 'updated_at' لتتبع وقت الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // حذف جدول 'settings' إذا تم التراجع عن الترحيل
        Schema::dropIfExists('settings');
    }
}

