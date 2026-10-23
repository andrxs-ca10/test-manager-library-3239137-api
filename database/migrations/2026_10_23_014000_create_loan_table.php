<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('member_id')->constrained('member')->restrictOnDelete();
            $table->foreignUuid('loaned_by_user_id')->constrained('user')->restrictOnDelete();
            $table->timestampTz('loaned_at');
        });

        Schema::table('loan', function (Blueprint $table) {
            $table->index('loaned_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan');
    }
};