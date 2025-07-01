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
        Schema::create('resource_trackings', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->unsignedBigInteger('unit_id')->comment('الوحدة المرتبطة'); // معرف الوحدة
            $table->integer('working_hours')->default(0)->comment('إجمالي ساعات العمل');
            
            // العمود الجديد المدمج
            $table->float('total_supplies_and_tools_score')->default(0)->comment('إجمالي نقاط المستلزمات والمعدات المستهلكة');
            
            $table->text('notes')->nullable()->comment('ملاحظات إضافية');
            $table->timestamps();

            // إضافة قيد فريد لضمان عدم وجود أكثر من سجل تتبع موارد لـ unit_id معين في تاريخ معين
            $table->unique(['date', 'unit_id']);
            
            // تعريف المفتاح الخارجي لـ unit_id
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_trackings');
    }
};