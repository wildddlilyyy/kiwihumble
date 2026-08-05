<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('class_shirt_orders')->delete();

        Schema::table('class_shirt_orders', function (Blueprint $table) {
            $table->json('items')->after('user_id');
            $table->unique('user_id');
            $table->dropColumn(['category', 'size', 'quantity']);
        });
    }

    public function down(): void
    {
        DB::table('class_shirt_orders')->delete();

        Schema::table('class_shirt_orders', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->string('category', 20)->after('user_id');
            $table->string('size', 20)->after('category');
            $table->unsignedInteger('quantity')->after('size');
            $table->dropColumn('items');
        });
    }
};
