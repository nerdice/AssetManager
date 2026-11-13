<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('component_installations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('component_id')
                ->constrained()
                ->restrictOnDelete();

            $table->timestamp('installed_at');
            $table->timestamp('removed_at')->nullable();

            $table->string('slot')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['asset_id', 'removed_at']);
            $table->index(['component_id', 'removed_at']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('component_installations');
    }
};
