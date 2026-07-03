<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('rental_start_date')->nullable()->after('address');
            $table->date('rental_end_date')->nullable()->after('rental_start_date');
            $table->integer('rental_days')->default(1)->after('rental_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['rental_start_date', 'rental_end_date', 'rental_days']);
        });
    }
};
