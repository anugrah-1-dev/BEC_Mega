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
        // Courses Table
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        // Periods Table
        if (!Schema::hasTable('periods')) {
            Schema::create('periods', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g., "April 2026"
                $table->string('date')->nullable(); // For legacy compatibility
                $table->date('start_date');
                $table->timestamps();
            });
        }

        // Student Details Table (Extended User info)
        if (!Schema::hasTable('student_details')) {
            Schema::create('student_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('phone');
                $table->string('address');
                $table->string('gender')->nullable();
                $table->date('birth_date')->nullable();
                $table->timestamps();
            });
        }

        // Registrations Table (links User, Course, Period)
        if (!Schema::hasTable('registrations')) {
            Schema::create('registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->foreignId('period_id')->constrained()->onDelete('cascade');
                $table->string('payment_proof')->nullable();
                $table->string('status')->default('pending'); // pending, verified, rejected, completed
                                $table->string('payment_status')->default('unpaid'); // unpaid, pending_validation, paid
                $table->timestamps();
            });
        }

        // Registration Comments (Feedback/Admin notes)
        if (!Schema::hasTable('registration_comments')) {
            Schema::create('registration_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('registration_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who made the comment
                $table->text('comment');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_comments');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('student_details');
        Schema::dropIfExists('periods');
        Schema::dropIfExists('courses');
    }
};
