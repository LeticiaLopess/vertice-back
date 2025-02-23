<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'disqualified', 'annulled'])->default('active');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->text('answer_a');
            $table->text('answer_b');
            $table->text('answer_c');
            $table->text('answer_d');
            $table->text('answer_e');
            $table->string('correct_answer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
