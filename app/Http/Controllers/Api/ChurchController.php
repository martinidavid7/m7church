<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Church; // Nota: O padrão do Laravel é 'App\Models\Church'


class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(church $church)
    {
        // Busca o primeiro registro da igreja.
        // Se houver múltiplas igrejas, você precisará de uma lógica para selecionar a correta.
        $church = Church::first();
       
        // Retorna os dados da igreja diretamente. O status 200 é o padrão.
        return response()->json($church);
    }   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
