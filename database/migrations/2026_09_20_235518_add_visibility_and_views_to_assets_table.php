<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->boolean('is_public')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);

            $table->index(['is_public', 'views_count']);
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex(['is_public', 'views_count']);
            $table->dropColumn(['is_public', 'views_count']);
        });
    }
};
