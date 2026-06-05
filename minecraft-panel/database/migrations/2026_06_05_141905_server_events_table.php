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
        Schema::create('server_events', function (Blueprint $table) {
            $table->id();

            $table->string('event_type');
            $table->string('player_name')->nullable();

            $table->text('message')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamp('event_time');

            $table->timestamps();

            $table->index('event_type');
            $table->index('player_name');
            $table->index('event_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
