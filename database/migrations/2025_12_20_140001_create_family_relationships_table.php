<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('family_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('related_person_id')->constrained('persons')->onDelete('cascade');
            $table->enum('relationship_type', [
                'spouse',           // cônjuge
                'child',            // filho(a)
                'parent',           // pai/mãe
                'sibling',          // irmão(ã)
                'grandparent',      // avô/avó
                'grandchild',       // neto(a)
                'other'             // outro
            ]);
            $table->timestamps();

            // Garante que não haja duplicações
            $table->unique(['person_id', 'related_person_id', 'relationship_type'], 'unique_family_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_relationships');
    }
};
