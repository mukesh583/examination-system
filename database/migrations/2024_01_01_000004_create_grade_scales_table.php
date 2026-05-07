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
        Schema::create('grade_scales', function (Blueprint $table) {
            $table->id();
            $table->string('grade', 2)->unique();
            $table->decimal('min_marks', 5, 2);
            $table->decimal('max_marks', 5, 2);
            $table->decimal('grade_point', 3, 1);
            $table->boolean('is_passing')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index('grade');
            $table->index('is_passing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_scales');
    }
};
