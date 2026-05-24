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
        // Students
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('fullname');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Banks
        if (!Schema::hasTable('banks')) {
            Schema::create('banks', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('number')->nullable();
                $table->string('owner')->nullable();
                $table->timestamps();
            });
        }

        // Course Features
        if (!Schema::hasTable('course_features')) {
            Schema::create('course_features', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->timestamps();
            });
        }

        // Payments
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->integer('student_id')->nullable();
                $table->string('status')->default('success');
                $table->timestamps();
            });
        }

        // Transports
        if (!Schema::hasTable('transports')) {
            Schema::create('transports', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        // Permits
        if (!Schema::hasTable('permits')) {
            Schema::create('permits', function (Blueprint $table) {
                $table->id();
                $table->string('letter_code')->nullable();
                $table->text('reason')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
        Schema::dropIfExists('transports');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('course_features');
        Schema::dropIfExists('banks');
        Schema::dropIfExists('students');
    }
};
