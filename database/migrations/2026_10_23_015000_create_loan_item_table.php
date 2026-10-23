<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_item', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('loan_id')->constrained('loan')->cascadeOnDelete();
            $table->foreignUuid('book_id')->constrained('book')->restrictOnDelete();
            $table->string('book_title', 250);
            $table->char('isbn', 13);
            $table->string('category_name', 120);
            $table->timestampTz('due_at');
            $table->timestampTz('returned_at')->nullable();

            // Restricción única: un mismo libro no se repite en el mismo préstamo
            $table->unique(['loan_id', 'book_id']);
        });

        // Índice para book_id
        Schema::table('loan_item', function (Blueprint $table) {
            $table->index('book_id');
        });

        // Índice parcial para vencidos (líneas sin devolver)
        DB::statement('CREATE INDEX loan_item_due_at_unreturned_idx ON library.loan_item (due_at) WHERE returned_at IS NULL;');
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_item');
    }
};