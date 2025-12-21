<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\UF;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $cities = City::with('uf')->orderBy('name')->paginate(15);
        return view('registrations.cities_list', ['cities' => $cities]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $uf = UF::all();
        return view('registrations.city_create', ['uf' => $uf]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'uf_id' => 'required|exists:ufs,id',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'exists' => 'A UF selecionada não é válida',
            ];

            $attributes = [
                'name' => 'nome da cidade',
                'uf_id' => 'UF',
            ];

            $request->validate($rules, $feedback, $attributes);

            City::create($request->only(['name', 'uf_id']));

            return redirect()->route('cities.index')->with('success', 'Cidade cadastrada com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('cities.create')->withInput()->with('error', 'Erro ao cadastrar a cidade!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        try {
            $city->load('uf');
            return view('registrations.city_show', ['city' => $city]);
        } catch (Exception $e) {
            return redirect()->route('cities.index')->with('error', 'Cidade não encontrada!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        try {
            $uf = UF::all();
            return view('registrations.city_edit', ['city' => $city, 'uf' => $uf]);
        } catch (Exception $e) {
            return redirect()->route('cities.index')->with('error', 'Cidade não encontrada!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city): RedirectResponse
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'uf_id' => 'required|exists:ufs,id',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'exists' => 'A UF selecionada não é válida',
            ];

            $attributes = [
                'name' => 'nome da cidade',
                'uf_id' => 'UF',
            ];

            $request->validate($rules, $feedback, $attributes);

            $city->update($request->only(['name', 'uf_id']));

            return redirect()->route('cities.index')->with('success', 'Cidade atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('cities.edit', $city)->withInput()->with('error', 'Erro ao atualizar a cidade!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city): RedirectResponse
    {
        try {
            $city->delete();

            return redirect()->route('cities.index')->with('success', 'Cidade excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('cities.index')->with('error', 'Erro ao excluir a cidade! Verifique se não há registros vinculados.');
        }
    }
}
