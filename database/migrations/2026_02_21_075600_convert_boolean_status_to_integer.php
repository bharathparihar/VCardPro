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
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                $driver = DB::getDriverName();

                if ($driver === 'pgsql') {
                    // PostgreSQL requires explicit cast from boolean to integer
                    DB::statement("ALTER TABLE {$table} ALTER COLUMN status TYPE integer USING (status::integer)");
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
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                $driver = DB::getDriverName();

                if ($driver === 'pgsql') {
                    DB::statement("ALTER TABLE {$table} ALTER COLUMN status TYPE boolean USING (status::boolean)");
                } else {
                    Schema::table($table, function (Blueprint $tableAlter) {
                        $tableAlter->boolean('status')->change();
                    });
                }
            }
        }
    }
};
