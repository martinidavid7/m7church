<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Church;
use App\Models\City;
use App\Models\UF;
use App\Models\Person;
use App\Models\ChurchType;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $church = Church::with(['pastor', 'churchType', 'parentChurch'])->get();
        return view('registrations.church_list', ['churchFull' => $church]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::all();
        $uf = UF::all();
        $persons = Person::where('active', 1)->orderBy('name')->get();
        $churchTypes = ChurchType::all();
        $churches = Church::orderBy('church_name')->get();
        return view('registrations.church_create', [
            'uf' => $uf,
            'cities' => $cities,
            'persons' => $persons,
            'churchTypes' => $churchTypes,
            'churches' => $churches
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação
            $rules = [
                'church_name' => 'required|string|max:255',
                'church_type_id' => 'required|exists:church_types,id',
                'parent_church_id' => 'nullable|exists:churches,id',
                'pastor_id' => 'nullable|exists:persons,id',
                'city_id' => 'nullable|exists:cities,id',
                'church_phone' => 'nullable|string|max:20',
                'church_mail' => 'nullable|email|max:255',
                'zip_code' => 'nullable|string|max:9',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'exists' => 'O :attribute selecionado não é válido',
                'email' => 'O campo :attribute deve ser um e-mail válido',
                'image' => 'O campo :attribute deve ser uma imagem',
                'mimes' => 'O campo :attribute deve ser do tipo: :values',
                'max' => 'O campo :attribute não pode ter mais de :max caracteres',
            ];

            $attributes = [
                'church_name' => 'nome da igreja',
                'church_type_id' => 'tipo de igreja',
                'parent_church_id' => 'igreja pai',
                'pastor_id' => 'pastor',
                'city_id' => 'cidade',
                'church_phone' => 'telefone',
                'church_mail' => 'e-mail',
                'zip_code' => 'CEP',
                'logo' => 'logo',
            ];

            $request->validate($rules, $feedback, $attributes);

            // Limpar máscaras de CEP e telefone
            $request->merge([
                'zip_code' => preg_replace('/[^0-9]/', '', $request->zip_code ?? ''),
                'church_phone' => preg_replace('/[^0-9]/', '', $request->church_phone ?? ''),
            ]);

            // Upload do logo se existir
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('churches/logos', 'public');
            }

            // Preparar dados (apenas campos que existem na tabela)
            $churchData = $request->only([
                'church_name',
                'address',
                'number',
                'neighborhood',
                'complement',
                'zip_code',
                'city_id',
                'church_phone',
                'church_mail',
                'pastor_id',
                'church_type_id',
                'parent_church_id'
            ]);

            if ($logoPath) {
                $churchData['logo'] = $logoPath;
            }

            Church::create($churchData);

            return redirect()->route('church.index')->with('success', 'Dados cadastrados com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retorna para o formulário com os erros de validação
            return redirect()->route('church.create')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Log do erro para debug
            \Log::error('Erro ao cadastrar igreja: ' . $e->getMessage());

            return redirect()->route('church.create')
                ->withInput()
                ->with('error', 'Erro ao cadastrar igreja: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $church = Church::findOrFail($id);
            $cities = City::all();
            $uf = UF::all();
            $persons = Person::where('active', 1)->orderBy('name')->get();
            $churchTypes = ChurchType::all();
            $churches = Church::where('id', '!=', $id)->orderBy('church_name')->get();
            return view('registrations.church', [
                'church' => $church,
                'cities' => $cities,
                'uf' => $uf,
                'persons' => $persons,
                'churchTypes' => $churchTypes,
                'churches' => $churches
            ]);
        } catch (\Exception $e) {
            return redirect()->route('church.index')->with('error', 'Igreja não encontrada ou erro ao carregar.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $church = Church::findOrFail($id);

            // Validação
            $rules = [
                'church_name' => 'required|string|max:255',
                'church_type_id' => 'required|exists:church_types,id',
                'parent_church_id' => 'nullable|exists:churches,id|not_in:' . $id,
                'pastor_id' => 'nullable|exists:persons,id',
                'city_id' => 'nullable|exists:cities,id',
                'church_phone' => 'nullable|string|max:20',
                'church_mail' => 'nullable|email|max:255',
                'zip_code' => 'nullable|string|max:9',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'exists' => 'O :attribute selecionado não é válido',
                'not_in' => 'Uma igreja não pode ser pai dela mesma',
                'email' => 'O campo :attribute deve ser um e-mail válido',
                'image' => 'O campo :attribute deve ser uma imagem',
                'mimes' => 'O campo :attribute deve ser do tipo: :values',
                'max' => 'O campo :attribute não pode ter mais de :max caracteres',
            ];

            $attributes = [
                'church_name' => 'nome da igreja',
                'church_type_id' => 'tipo de igreja',
                'parent_church_id' => 'igreja pai',
                'pastor_id' => 'pastor',
                'city_id' => 'cidade',
                'church_phone' => 'telefone',
                'church_mail' => 'e-mail',
                'zip_code' => 'CEP',
                'logo' => 'logo',
            ];

            $request->validate($rules, $feedback, $attributes);

            // Limpar máscaras de CEP e telefone
            $request->merge([
                'zip_code' => preg_replace('/[^0-9]/', '', $request->zip_code ?? ''),
                'church_phone' => preg_replace('/[^0-9]/', '', $request->church_phone ?? ''),
            ]);

            // Preparar dados para atualização (excluir campos que não existem na tabela)
            $churchData = $request->only([
                'church_name',
                'address',
                'number',
                'neighborhood',
                'complement',
                'zip_code',
                'city_id',
                'church_phone',
                'church_mail',
                'pastor_id',
                'church_type_id',
                'parent_church_id'
            ]);

            // Upload do logo se houver
            if ($request->hasFile('logo')) {
                // Deletar logo antigo se existir
                if ($church->logo && \Storage::disk('public')->exists($church->logo)) {
                    \Storage::disk('public')->delete($church->logo);
                }
                $churchData['logo'] = $request->file('logo')->store('churches/logos', 'public');
            }

            $church->update($churchData);

            return redirect()->route('church.index')->with('success', 'Dados atualizados com sucesso!');
        } catch (\Exception $e) {
            // Log do erro para debug
            \Log::error('Erro ao atualizar igreja: ' . $e->getMessage());

            return redirect()->route('church.edit', ['id' => $id])
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar a igreja: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get cities by UF id
     */
    public function getCitiesByUf($uf_id)
    {
        $cities = City::where('uf_id', $uf_id)->get();
        return response()->json($cities);
    }
}
