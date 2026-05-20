<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel additional_services
        Schema::create('additional_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tambah kolom additional_service_id ke registrations
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('additional_service_id')
                  ->nullable()
                  ->after('transport_id')
                  ->constrained('additional_services')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['additional_service_id']);
            $table->dropColumn('additional_service_id');
        });

        Schema::dropIfExists('additional_services');
    }
};
