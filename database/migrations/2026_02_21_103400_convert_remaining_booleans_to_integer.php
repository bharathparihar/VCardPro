<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert remaining boolean columns to integer for PostgreSQL compatibility.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1. Tables with a 'status' boolean column not yet converted
        $statusTables = [
            'coupon_codes'              => 0,
            'add_ons'                   => 1,
            'product_transactions'      => 0,
            'appointment_transactions'  => 0,
            'schedule_appointments'     => 0,
            'blogs'                     => 1,
        ];

        foreach ($statusTables as $table => $default) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                if ($driver === 'pgsql') {
                    try {
                        DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status DROP DEFAULT");
                        DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status TYPE integer USING (CASE WHEN status IS TRUE THEN 1 WHEN status IS FALSE THEN 0 ELSE CAST(status AS integer) END)");
                        DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status SET DEFAULT {$default}");
                    } catch (\Exception $e) {
                        // Column may already be integer - skip
                    }
                } else {
                    Schema::table($table, function (Blueprint $t) {
                        $t->integer('status')->change();
                    });
                }
            }
        }

        // 2. affiliate_users.is_verified
        if (Schema::hasTable('affiliate_users') && Schema::hasColumn('affiliate_users', 'is_verified')) {
            if ($driver === 'pgsql') {
                try {
                    DB::statement('ALTER TABLE "affiliate_users" ALTER COLUMN is_verified DROP DEFAULT');
                    DB::statement('ALTER TABLE "affiliate_users" ALTER COLUMN is_verified TYPE integer USING (CASE WHEN is_verified IS TRUE THEN 1 ELSE 0 END)');
                    DB::statement('ALTER TABLE "affiliate_users" ALTER COLUMN is_verified SET DEFAULT 0');
                } catch (\Exception $e) {
                    // Already integer
                }
            } else {
                Schema::table('affiliate_users', function (Blueprint $t) {
                    $t->integer('is_verified')->default(0)->change();
                });
            }
        }

        // 3. custom_domain boolean columns
        $customDomainBooleans = [
            'is_approved'  => 0,
            'is_active'    => 0,
            'is_use_vcard' => 0,
        ];

        if (Schema::hasTable('custom_domain')) {
            foreach ($customDomainBooleans as $column => $default) {
                if (Schema::hasColumn('custom_domain', $column)) {
                    if ($driver === 'pgsql') {
                        try {
                            DB::statement("ALTER TABLE \"custom_domain\" ALTER COLUMN {$column} DROP DEFAULT");
                            DB::statement("ALTER TABLE \"custom_domain\" ALTER COLUMN {$column} TYPE integer USING (CASE WHEN {$column} IS TRUE THEN 1 ELSE 0 END)");
                            DB::statement("ALTER TABLE \"custom_domain\" ALTER COLUMN {$column} SET DEFAULT {$default}");
                        } catch (\Exception $e) {
                            // Already integer
                        }
                    } else {
                        Schema::table('custom_domain', function (Blueprint $t) use ($column, $default) {
                            $t->integer($column)->default($default)->change();
                        });
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        $statusTables = [
            'coupon_codes',
            'add_ons',
            'product_transactions',
            'appointment_transactions',
            'schedule_appointments',
            'blogs',
        ];

        foreach ($statusTables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                if ($driver === 'pgsql') {
                    try {
                        DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status DROP DEFAULT");
                        DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status TYPE boolean USING (CASE WHEN status = 1 THEN TRUE ELSE FALSE END)");
                        DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status SET DEFAULT FALSE");
                    } catch (\Exception $e) {
                    }
                } else {
                    Schema::table($table, function (Blueprint $t) {
                        $t->boolean('status')->change();
                    });
                }
            }
        }

        if (Schema::hasTable('affiliate_users') && Schema::hasColumn('affiliate_users', 'is_verified')) {
            if ($driver === 'pgsql') {
                try {
                    DB::statement('ALTER TABLE "affiliate_users" ALTER COLUMN is_verified DROP DEFAULT');
                    DB::statement('ALTER TABLE "affiliate_users" ALTER COLUMN is_verified TYPE boolean USING (CASE WHEN is_verified = 1 THEN TRUE ELSE FALSE END)');
                    DB::statement('ALTER TABLE "affiliate_users" ALTER COLUMN is_verified SET DEFAULT FALSE');
                } catch (\Exception $e) {
                }
            } else {
                Schema::table('affiliate_users', function (Blueprint $t) {
                    $t->boolean('is_verified')->default(0)->change();
                });
            }
        }

        $customDomainBooleans = ['is_approved', 'is_active', 'is_use_vcard'];

        if (Schema::hasTable('custom_domain')) {
            foreach ($customDomainBooleans as $column) {
                if (Schema::hasColumn('custom_domain', $column)) {
                    if ($driver === 'pgsql') {
                        try {
                            DB::statement("ALTER TABLE \"custom_domain\" ALTER COLUMN {$column} DROP DEFAULT");
                            DB::statement("ALTER TABLE \"custom_domain\" ALTER COLUMN {$column} TYPE boolean USING (CASE WHEN {$column} = 1 THEN TRUE ELSE FALSE END)");
                            DB::statement("ALTER TABLE \"custom_domain\" ALTER COLUMN {$column} SET DEFAULT FALSE");
                        } catch (\Exception $e) {
                        }
                    } else {
                        Schema::table('custom_domain', function (Blueprint $t) use ($column) {
                            $t->boolean($column)->default(0)->change();
                        });
                    }
                }
            }
        }
    }
};
