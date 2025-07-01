<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل المهاجرين (Run the migrations).
     */
    public function up(): void
    {
        Schema::create('service_tasks', function (Blueprint $table) {
            $table->id(); // عمود المعرف الرئيسي التزايدي
            $table->string('title'); // عنوان المهمة
            $table->text('description')->nullable(); // وصف المهمة (يمكن أن يكون فارغاً)
            // تم تعديل القيمة الافتراضية لتتناسب مع مفاتيح ثابت UNITS في موديل ServiceTask
            $table->string('unit')->default('GeneralCleaning'); // وحدة المهمة: 'GeneralCleaning' أو 'SanitationFacility'
            $table->string('status')->default('pending'); // حالة المهمة: 'pending', 'in_progress', 'completed', 'rejected'
            $table->integer('order_column')->nullable(); // عمود لترتيب المهام (مهم لـ spatie/laravel-sortable)
            $table->date('due_date')->nullable(); // تاريخ الاستحقاق للمهمة (يمكن أن يكون فارغاً)
            // عمود المفتاح الأجنبي الذي يشير إلى جدول 'employees'
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('priority')->default('medium'); // أولوية المهمة: 'low', 'medium', 'high', 'urgent' (إذا أضفت 'urgent' للموديل)
            $table->timestamps(); // أعمدة created_at و updated_at
        });
    }

    /**
     * التراجع عن المهاجرين (Reverse the migrations).
     */
    public function down(): void
    {
        Schema::dropIfExists('service_tasks'); // حذف الجدول إذا كان موجوداً
    }
};
