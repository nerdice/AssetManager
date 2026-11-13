<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('asset_type_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name');
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();

            $table->string('status')->default('active');
            $table->string('condition')->default('excellent');

            $table->string('acquisition_condition')->nullable();
            $table->string('acquisition_method')->nullable();

            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('seller')->nullable();
            $table->date('warranty_expires_at')->nullable();

            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->json('specifications')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
