<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'subscriptions',
            'vcards',
            'languages',
            'custom_pages',
            'whatsapp_stores',
            'plans',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                $driver = DB::getDriverName();

                if ($driver === 'pgsql') {
                    // PostgreSQL requires explicit cast from boolean to integer
                    // Drop default first to avoid type mismatch
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status DROP DEFAULT");
                    
                    // Change type using CASE for maximum safety with boolean data
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status TYPE integer USING (CASE WHEN status IS TRUE THEN 1 ELSE 0 END)");
                    
                    // Apply appropriate defaults
                    $default = ($table === 'subscriptions') ? 0 : 1;
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status SET DEFAULT {$default}");
                } else {
                    Schema::table($table, function (Blueprint $tableAlter) {
                        $tableAlter->integer('status')->change();
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'subscriptions',
            'vcards',
            'languages',
            'custom_pages',
            'whatsapp_stores',
            'plans',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                $driver = DB::getDriverName();

                if ($driver === 'pgsql') {
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status DROP DEFAULT");
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status TYPE boolean USING (CASE WHEN status = 1 THEN TRUE ELSE FALSE END)");
                    $default = ($table === 'subscriptions') ? 'FALSE' : 'TRUE';
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN status SET DEFAULT {$default}");
                } else {
                    Schema::table($table, function (Blueprint $tableAlter) {
                        $tableAlter->boolean('status')->change();
                    });
                }
            }
        }
    }
};
