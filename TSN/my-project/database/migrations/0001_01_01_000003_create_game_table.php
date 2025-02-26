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
        Schema::create('games', function (Blueprint $table) {
            $table->id();  // Creates an auto-incrementing ID column
            $table->string('x', 255);  // Creates a varchar column for 'x' with a max length of 255 characters
            $table->string('o', 255)->nullable();  // Creates a nullable varchar column for 'o'
            $table->string('board', 37);  // Creates a varchar column for 'board' with a max length of 37 characters
            $table->string('winner', 1)->nullable();  // Creates a nullable varchar column for 'winner' with a max length of 1 character
            $table->integer('revenge')->default(0);  // Creates an integer column for 'revenge' with a default value of 0
            // $table->timestamps(0);  // Creates 'created_at' and 'updated_at' columns as datetime (with no milliseconds)
            $table->timestamp('created_at')->useCurrent()->immutable();
            $table->timestamp('updated_at')->useCurrent()->nullable()->immutable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
