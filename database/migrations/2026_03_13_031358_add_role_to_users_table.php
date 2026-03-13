<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role column to users table with default value 'viewer'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['viewer', 'editor', 'admin'])
                  ->default('viewer')
                  ->after('email');
        });
    }

    public function down(): void
    {
        // Remove the role column from the users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};