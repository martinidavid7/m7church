<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // A execução anterior desta migration pode ter ficado parcial (o MySQL não reverte
        // DDL em transação): a FK de service_id já pode ter sido removida. Verificamos o
        // estado real antes de agir, para a migration ser segura de re-rodar.
        $hasServiceForeignKey = collect(DB::select("
            SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'schedules'
                AND COLUMN_NAME = 'service_id' AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->isNotEmpty();

        $hasMinistryForeignKey = collect(DB::select("
            SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'schedules'
                AND COLUMN_NAME = 'ministry_id' AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->isNotEmpty();

        if ($hasServiceForeignKey || $hasMinistryForeignKey) {
            Schema::table('schedules', function (Blueprint $table) use ($hasServiceForeignKey, $hasMinistryForeignKey) {
                if ($hasServiceForeignKey) {
                    $table->dropForeign(['service_id']);
                }
                if ($hasMinistryForeignKey) {
                    $table->dropForeign(['ministry_id']);
                }
            });
        }

        $hasUniqueIndex = collect(DB::select("SHOW INDEX FROM schedules WHERE Key_name = 'schedules_ministry_id_service_id_date_unique'"))->isNotEmpty();

        if ($hasUniqueIndex) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropUnique(['ministry_id', 'service_id', 'date']);
            });
        }

        if (!Schema::hasColumn('schedules', 'title')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->string('title')->nullable()->after('service_id');
            });
        }

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('ministry_id')->references('id')->on('ministries')->cascadeOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropForeign(['ministry_id']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->foreignId('service_id')->nullable(false)->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('ministry_id')->references('id')->on('ministries')->cascadeOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
            $table->unique(['ministry_id', 'service_id', 'date']);
        });
    }
};
