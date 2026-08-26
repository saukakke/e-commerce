<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_transactions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
        });
        Schema::table('vendor_payouts', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_payouts', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('account_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_transactions', 'paid_at')) $table->dropColumn('paid_at');
        });
        Schema::table('vendor_payouts', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_payouts', 'rejection_reason')) $table->dropColumn('rejection_reason');
        });
    }
};
