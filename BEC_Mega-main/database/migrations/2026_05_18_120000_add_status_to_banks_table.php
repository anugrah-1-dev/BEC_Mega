<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('banks') && !Schema::hasColumn('banks', 'status')) {
            Schema::table('banks', function (Blueprint $table) {
                $table->string('status')->default('active')->after('owner');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('banks') && Schema::hasColumn('banks', 'status')) {
            Schema::table('banks', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
