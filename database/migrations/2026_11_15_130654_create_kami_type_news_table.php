<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kami_type_news', function (Blueprint $table): void {
            $table->foreignUlid('entry_id')->constrained('kami_entries', indexName: 'kami_fk_afb9b263d958af4a55ddd030b050bb51')->cascadeOnDelete();
            $table->string('locale', 20);
            $table->text('body')->nullable();
            $table->unique(['entry_id', 'locale'], 'kami_idx_d0e155149e445d4351ab2f03a8799884');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kami_type_news');
    }
};
