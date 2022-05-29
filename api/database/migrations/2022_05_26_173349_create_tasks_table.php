<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid()->nullable();
            $table->foreignId('users_id')->constrained('users');
            $table->string('name', 128)->index('idx_tasks_name');
            $table->string('description', 1024);
            $table->enum('status', ['created', 'started', 'cancelled', 'completed']);
            $table->dateTime('completed_datetime')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
