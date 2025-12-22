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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->foreignId('award_id')->constrained('awards')->onDelete('cascade');
            $table->string('application_number')->unique();
            $table->date('application_date');
            $table->enum('application_status', ['pending', 'under_review', 'accepted', 'rejected'])->default('pending');
            $table->text('supporting_documents')->nullable();
            $table->unsignedBigInteger('jamb_score')->nullable();            
            $table->timestamps();

            $table->unique(['applicant_id', 'award_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
