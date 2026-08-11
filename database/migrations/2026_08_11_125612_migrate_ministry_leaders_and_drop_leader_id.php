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
        // Migra os líderes atuais (ministries.leader_id -> users.id) para o vínculo
        // person_ministry (via persons.user_id), antes de remover a coluna antiga.
        $ministries = DB::table('ministries')->whereNotNull('leader_id')->get(['id', 'leader_id']);

        foreach ($ministries as $ministry) {
            $person = DB::table('persons')->where('user_id', $ministry->leader_id)->first(['id']);

            if ($person) {
                DB::table('person_ministry')->updateOrInsert(
                    ['person_id' => $person->id, 'ministry_id' => $ministry->id],
                    ['role' => 'lider', 'updated_at' => now(), 'created_at' => now()]
                );
            }
        }

        Schema::table('ministries', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
            $table->dropColumn('leader_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministries', function (Blueprint $table) {
            $table->unsignedBigInteger('leader_id')->nullable()->after('logo');
        });

        Schema::table('ministries', function (Blueprint $table) {
            $table->foreign('leader_id')->references('id')->on('users');
        });
    }
};
