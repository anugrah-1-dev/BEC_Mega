<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('language')->default('Inggris')->after('name');
            $table->string('type')->default('Offline')->after('language');     // Offline / Online
            $table->string('duration')->nullable()->after('type');              // e.g. "30 Hari"
            $table->decimal('admin_tax', 15, 2)->default(0)->after('price');   // biaya admin
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['language', 'type', 'duration', 'admin_tax']);
        });
    }
};
