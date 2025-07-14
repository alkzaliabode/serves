<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_sanitation_summary', function (Blueprint $table) {
            $table->string('id')->primary(); // ID مركب من الشهر + المنشأة + المهمة
            $table->string('month', 7)->index();
            $table->string('facility_name');
            $table->string('task_type');
            $table->unsignedBigInteger('unit_id'); // ✅ إضافة عمود unit_id
            $table->integer('total_seats')->default(0);
            $table->integer('total_mirrors')->default(0);
            $table->integer('total_mixers')->default(0);
            $table->integer('total_doors')->default(0);
            $table->integer('total_sinks')->default(0);
            $table->integer('total_toilets')->default(0);
            $table->integer('total_tasks')->default(0);
            $table->timestamps();

            // ✅ إضافة مفتاح أجنبي إذا كنت ترغب في ربط الملخص بالوحدات
            // $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            // تأكد من أن جدول 'units' موجود قبل تفعيل هذا السطر.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_sanitation_summary');
    }
};
