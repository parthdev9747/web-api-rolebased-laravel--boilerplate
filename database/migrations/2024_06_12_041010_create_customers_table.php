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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('point_of_contact_name')->nullable();
            $table->string('point_of_contact_email')->nullable();
            $table->string('point_of_contact_phone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->integer('country')->default(0);
            $table->string('company_website')->nullable();
            $table->longText('shipping_instructions')->nullable();
            $table->longText('shipping_terms_note')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
