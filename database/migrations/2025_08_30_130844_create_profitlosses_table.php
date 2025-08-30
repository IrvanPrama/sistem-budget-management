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
        Schema::create('profitlosses', function (Blueprint $table) {
            $table->id();
            $table->date ('date');
            $table->string('income');
            $table->string('operation-fee');
            $table->string('employee_salary');
            $table->string('other_fee');
            $table->string('total_cost');
            $table->string('profit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profitlosses');
    }
};
