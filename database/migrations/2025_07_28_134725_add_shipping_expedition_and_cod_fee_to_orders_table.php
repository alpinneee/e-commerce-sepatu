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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('cod_fee', 12, 2)->default(0)->after('discount_amount');
            $table->string('shipping_expedition')->nullable()->after('shipping_address');
            $table->string('shipping_expedition_name')->nullable()->after('shipping_expedition');
            $table->string('shipping_estimation')->nullable()->after('shipping_expedition_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cod_fee', 'shipping_expedition', 'shipping_expedition_name', 'shipping_estimation']);
        });
    }
};
