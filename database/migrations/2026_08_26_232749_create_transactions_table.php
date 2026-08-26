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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('account_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('type', 100);
            $table->decimal('amount', 15, 2)
                ->default(0);
            $table->text('description');
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
