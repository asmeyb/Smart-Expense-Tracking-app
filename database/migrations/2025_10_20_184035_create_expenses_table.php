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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->decimal('amount',10,2);
            $table->string('title');
            $table->string('description')->nullable();
            $table->date('date');
            $table->enum('type',['one-time', 'recurring'])->default('one-time');

            $table->enum('recurring_frequency',['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->date('recurring_start_date')->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->foreignID('parent_expense_id')->nullable()->constrained('expenses')->nullOnDelete();
            $table->boolean('is_auto_generated')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id', 'date');
            $table->index('user_id', 'type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
