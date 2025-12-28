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
        // For SQLite in development, specifically for adding an enum value, 
        // sometimes it's easier to just assume the string constraint is handled by the application 
        // or recreate the whole table if strictly enforced.
        // However, Laravel's schema builder might not support changing enum options directly for SQLite.
        // We will try to modify the column to standard string to be more flexible, or just rely on new check constraint.
        
        // Since we are likely on SQLite for this user (based on previous logs about SQLite functions),
        // DB::statement("ALTER TABLE applications DROP CHECK application_status_check"); // hypothetical name
        
        // A safer way in Laravel for SQLite (using Doctrine DBAL if available) or raw SQL:
        
        Schema::table('applications', function (Blueprint $table) {
             // In many DBs, we'd do this. But SQLite has valid check constraints.
             // We'll change it to string regarding schema abstraction, default 'pending'
             $table->string('application_status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Revert is tricky if data exists, but we can try setting it back to enum
            // $table->enum('application_status', ['pending', 'under_review', 'accepted', 'rejected'])->default('pending')->change();
        });
    }
};
