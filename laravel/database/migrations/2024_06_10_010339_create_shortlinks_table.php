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
        Schema::create('shortlinks', function (Blueprint $table) {
            $table->id();
            $table->string('shortid')->unique();
            $table->string('destination', 5000);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->integer('max_clicks')->default(0);
            $table->boolean('deleted')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('link_interactions', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->foreign('link')->references('shortid')->on('shortlinks');
            $table->string('ip');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortlinks');
        Schema::dropIfExists('link_interactions');
    }
};
