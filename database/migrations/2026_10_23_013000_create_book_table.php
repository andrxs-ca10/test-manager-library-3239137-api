<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 250);
            $table->string('author', 200);
            $table->char('isbn', 13);
            $table->foreignUuid('category_id')->constrained('category')->restrictOnDelete();
            $table->integer('total_copies');
            $table->integer('available_copies');
            $table->string('cover_key', 512)->nullable();
            $table->timestampTz('deleted_at')->nullable();
        });

        DB::statement('CREATE UNIQUE INDEX book_isbn_active_unique ON library.book (isbn) WHERE deleted_at IS NULL;');
        DB::statement('ALTER TABLE library.book ADD CONSTRAINT ck_book_available_range CHECK (available_copies >= 0 AND available_copies <= total_copies);');
        DB::statement('CREATE INDEX book_category_title_active_idx ON library.book (category_id, title) WHERE deleted_at IS NULL;');
    }

    public function down(): void
    {
        Schema::dropIfExists('book');
    }
};