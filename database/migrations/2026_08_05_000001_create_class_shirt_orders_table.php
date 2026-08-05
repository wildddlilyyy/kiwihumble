<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_shirt_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 20);
            $table->string('size', 20);
            $table->unsignedInteger('quantity');
            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_shirt_orders');
    }
};
