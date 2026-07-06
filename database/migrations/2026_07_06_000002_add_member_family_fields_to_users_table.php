<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('birthday')->nullable()->after('phone');
            $table->string('mom_phone')->nullable()->after('mom_name');
            $table->string('dad_phone')->nullable()->after('dad_name');
            $table->string('login_password')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birthday', 'mom_phone', 'dad_phone', 'login_password']);
        });
    }
};
