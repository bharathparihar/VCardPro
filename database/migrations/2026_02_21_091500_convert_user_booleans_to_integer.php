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
        if (Schema::hasTable('users')) {
            $driver = DB::getDriverName();
            
            if ($driver === 'pgsql') {
                // Fix is_active column
                if (Schema::hasColumn('users', 'is_active')) {
                    DB::statement('ALTER TABLE "users" ALTER COLUMN is_active DROP DEFAULT');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN is_active TYPE integer USING (CASE WHEN is_active IS TRUE THEN 1 ELSE 0 END)');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN is_active SET DEFAULT 1');
                }
                
                // Fix enable_two_factor_authentication column
                if (Schema::hasColumn('users', 'enable_two_factor_authentication')) {
                    DB::statement('ALTER TABLE "users" ALTER COLUMN enable_two_factor_authentication DROP DEFAULT');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN enable_two_factor_authentication TYPE integer USING (CASE WHEN enable_two_factor_authentication IS TRUE THEN 1 ELSE 0 END)');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN enable_two_factor_authentication SET DEFAULT 0');
                }
            } else {
                Schema::table('users', function (Blueprint $table) {
                    if (Schema::hasColumn('users', 'is_active')) {
                        $table->integer('is_active')->default(1)->change();
                    }
                    if (Schema::hasColumn('users', 'enable_two_factor_authentication')) {
                        $table->integer('enable_two_factor_authentication')->default(0)->change();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            $driver = DB::getDriverName();
            
            if ($driver === 'pgsql') {
                if (Schema::hasColumn('users', 'is_active')) {
                    DB::statement('ALTER TABLE "users" ALTER COLUMN is_active DROP DEFAULT');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN is_active TYPE boolean USING (CASE WHEN is_active = 1 THEN TRUE ELSE FALSE END)');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN is_active SET DEFAULT TRUE');
                }
                
                if (Schema::hasColumn('users', 'enable_two_factor_authentication')) {
                    DB::statement('ALTER TABLE "users" ALTER COLUMN enable_two_factor_authentication DROP DEFAULT');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN enable_two_factor_authentication TYPE boolean USING (CASE WHEN enable_two_factor_authentication = 1 THEN TRUE ELSE FALSE END)');
                    DB::statement('ALTER TABLE "users" ALTER COLUMN enable_two_factor_authentication SET DEFAULT FALSE');
                }
            } else {
                Schema::table('users', function (Blueprint $table) {
                    if (Schema::hasColumn('users', 'is_active')) {
                        $table->boolean('is_active')->default(1)->change();
                    }
                    if (Schema::hasColumn('users', 'enable_two_factor_authentication')) {
                        $table->boolean('enable_two_factor_authentication')->default(0)->change();
                    }
                });
            }
        }
    }
};
