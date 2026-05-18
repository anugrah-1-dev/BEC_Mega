<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom ke student_details
        Schema::table('student_details', function (Blueprint $table) {
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('uniform_size')->nullable()->after('birth_place');
            $table->string('guardian_phone')->nullable()->after('uniform_size');
        });

        // Tambah kolom ke registrations
        Schema::table('registrations', function (Blueprint $table) {
            $table->boolean('has_catering')->default(false)->after('transport_id');
            $table->boolean('has_laundry')->default(false)->after('has_catering');
            $table->boolean('has_holiday')->default(false)->after('has_laundry');
        });
    }

    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
            $table->dropColumn(['birth_place', 'uniform_size', 'guardian_phone']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['has_catering', 'has_laundry', 'has_holiday']);
        });
    }
};
