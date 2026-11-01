<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kami_type_home', function (Blueprint $table): void {
            $table->foreignUlid('entry_id')->constrained('kami_entries', indexName: 'kami_fk_8675a76b8509e7c217ef461b83ff9f77')->cascadeOnDelete();
            $table->string('locale', 20);
            $table->text('intro')->nullable();
            $table->unique(['entry_id', 'locale'], 'kami_idx_5ffe843e99d9eb92bbb92b51dca2ea39');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kami_type_home');
    }
};
