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
        Schema::create('messages', function (Blueprint $table) {
            $table->ulid('idMessage')->primary();
            $table->foreignUlid('sender_id')->nullable()->references('idUser')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUlid('receiver_id')->nullable()->references('idUser')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('content');
            $table->timestamp('sent_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
