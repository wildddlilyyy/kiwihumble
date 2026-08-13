<?php

use App\Models\ClassShirtOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_shirt_orders', function (Blueprint $table) {
            $table->string('payment_method', 20)->nullable()->after('submitted_at');
            $table->string('payment_account_last_five', 5)->nullable()->after('payment_method');
            $table->string('payment_status', 20)->default(ClassShirtOrder::PAYMENT_STATUS_UNPAID)->after('payment_account_last_five');
        });

        DB::table('class_shirt_orders')
            ->whereNull('payment_status')
            ->update(['payment_status' => ClassShirtOrder::PAYMENT_STATUS_UNPAID]);
    }

    public function down(): void
    {
        Schema::table('class_shirt_orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_account_last_five',
                'payment_status',
            ]);
        });
    }
};
