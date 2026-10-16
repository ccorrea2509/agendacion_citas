<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'rol')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('rol')->default('Cliente')->after('email');
            });
        }
    }

    public function down(): void
    {
        // Keep the role column because older installations may have created it
        // with the original users migration before this compatibility migration.
    }
};
