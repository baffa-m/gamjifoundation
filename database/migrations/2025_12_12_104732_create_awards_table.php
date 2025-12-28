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
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('deadline')->nullable();
            $table->foreignId('sponsor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('required_documents')->nullable();
            $table->decimal('amount', 12, 2);
            $table->integer('number_of_awards')->default(1);
            $table->enum('category', ['jamb', 'waec', 'general']);
            $table->json('eligibility_criteria')->nullable(); // Store as JSON
            $table->date('application_start_date');
            $table->date('application_end_date');
            $table->date('announcement_date')->nullable();
            $table->enum('status', ['draft', 'active', 'closed', 'suspended'])->default('draft');
            $table->string('award_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};
