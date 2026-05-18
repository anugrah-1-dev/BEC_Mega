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
        Schema::table('registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('transport_id')->nullable()->after('period_id');
            // Jika ingin foreign key constraint tapi tabelnya mungkin sudah ada
            // $table->foreign('transport_id')->references('id')->on('transports')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('transport_id');
        });
    }
};
