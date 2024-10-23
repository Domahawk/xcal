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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_iso_code', 3);
            $table->decimal('buying_rate', 20, 16);
            $table->decimal('selling_rate', 20, 16);
            $table->decimal('average_rate', 20, 16);
            $table->date('application_date');
            $table->timestamps();

            $table->foreign('currency_iso_code')
                ->references('iso_code')
                ->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
