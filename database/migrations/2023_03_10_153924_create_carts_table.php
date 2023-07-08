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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->decimal('total')->nullable();
            $table->decimal('total_vat')->nullable();
            $table->enum('status',['Pending', 'Ordered', 'Processing','Shipping', 'Delivered'])->default('Pending');
            $table->enum('payment_option',['Cash', 'Visa'])->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shipping_address_id')->nullable()->constrained('shipping_addresses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
