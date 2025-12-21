<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\City;
use App\Models\UF;
use App\Exports\VisitorsExport;
use Maatwebsite\Excel\Facades\Excel;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Visitor::query();

        // Filtro por nome
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Filtro por data inicial
        if ($request->filled('startDate')) {
            $query->where('visit_date', '>=', $request->input('startDate'));
        }

        // Filtro por data final
        if ($request->filled('endDate')) {
            $query->where('visit_date', '<=', $request->input('endDate'));
        }

        $visitors = $query->orderBy('visit_date', 'desc')->orderBy('name')->paginate(15);

        return view('registrations.visitor_list', ['visitors' => $visitors]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::all();
        $uf = UF::all();
        return view('registrations.visitor_create', ['uf' => $uf, 'cities' => $cities]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $regras = [
            'name' => 'required|min:3|max:255',
            'visit_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:M,F',
            'marital_status' => 'nullable|string|max:50',
            'mail' => 'nullable|email|max:255',
            'mobile_phone' => 'required|string|max:20',
            'landline_phone' => 'nullable|string|max:20',
            'profession' => 'nullable|string|max:100',
            'education_level' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'complement' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:9',
            'city_id' => 'nullable|exists:cities,id',
            'user_id' => 'nullable|exists:users,id',
            'accept_receive_messages' => 'nullable|boolean',
            'observations' => 'nullable|string',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'email' => 'O campo :attribute deve ser um e-mail válido.',
            'date' => 'O campo :attribute deve ser uma data válida.',
            'exists' => 'O valor selecionado para :attribute é inválido.',
            'in' => 'O campo :attribute deve ser M ou F.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
        ];

        // Personalizar nomes dos atributos
        $attributes = [
            'name' => 'nome',
            'visit_date' => 'data da visita',
            'birth_date' => 'data de nascimento',
            'gender' => 'sexo',
            'marital_status' => 'estado civil',
            'mail' => 'e-mail',
            'mobile_phone' => 'telefone celular',
            'landline_phone' => 'telefone fixo',
            'profession' => 'profissão',
            'education_level' => 'escolaridade',
            'address' => 'endereço',
            'number' => 'número',
            'neighborhood' => 'bairro',
            'complement' => 'complemento',
            'zip_code' => 'CEP',
            'city_id' => 'cidade',
            'user_id' => 'usuário',
            'accept_receive_messages' => 'aceita receber mensagens',
            'observations' => 'observações',
        ];

        $request->validate($regras, $feedback, $attributes);

        // Limpar máscaras de telefones e CEP
        $request->merge([
            'zip_code' => preg_replace('/[^0-9]/', '', $request->zip_code ?? ''),
            'mobile_phone' => preg_replace('/[^0-9]/', '', $request->mobile_phone ?? ''),
            'landline_phone' => preg_replace('/[^0-9]/', '', $request->landline_phone ?? ''),
            'accept_receive_messages' => $request->has('accept_receive_messages') ? 1 : 0,
        ]);

        // Criar visitante apenas com os campos fillable
        $visitorData = $request->only([
            'name',
            'visit_date',
            'birth_date',
            'gender',
            'marital_status',
            'address',
            'number',
            'neighborhood',
            'complement',
            'zip_code',
            'landline_phone',
            'mobile_phone',
            'profession',
            'education_level',
            'city_id',
            'user_id',
            'mail',
            'accept_receive_messages',
            'observations',
        ]);

        Visitor::create($visitorData);

        return redirect()->route('visitors.index')
                         ->with('success', 'Visitante cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitor $visitor)
    {
        $cities = City::all();
        $uf = UF::all();
        return view('registrations.visitor_edit', [
            'visitor' => $visitor,
            'uf' => $uf,
            'cities' => $cities
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitor $visitor)
    {
        $regras = [
            'name' => 'required|min:3|max:255',
            'visit_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:M,F',
            'marital_status' => 'nullable|string|max:50',
            'mail' => 'nullable|email|max:255',
            'mobile_phone' => 'required|string|max:20',
            'landline_phone' => 'nullable|string|max:20',
            'profession' => 'nullable|string|max:100',
            'education_level' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'complement' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:9',
            'city_id' => 'nullable|exists:cities,id',
            'accept_receive_messages' => 'nullable|boolean',
            'observations' => 'nullable|string',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'email' => 'O campo :attribute deve ser um e-mail válido.',
            'date' => 'O campo :attribute deve ser uma data válida.',
            'exists' => 'O valor selecionado para :attribute é inválido.',
            'in' => 'O campo :attribute deve ser M ou F.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
        ];

        // Personalizar nomes dos atributos
        $attributes = [
            'name' => 'nome',
            'visit_date' => 'data da visita',
            'birth_date' => 'data de nascimento',
            'gender' => 'sexo',
            'marital_status' => 'estado civil',
            'mail' => 'e-mail',
            'mobile_phone' => 'telefone celular',
            'landline_phone' => 'telefone fixo',
            'profession' => 'profissão',
            'education_level' => 'escolaridade',
            'address' => 'endereço',
            'number' => 'número',
            'neighborhood' => 'bairro',
            'complement' => 'complemento',
            'zip_code' => 'CEP',
            'city_id' => 'cidade',
            'accept_receive_messages' => 'aceita receber mensagens',
            'observations' => 'observações',
        ];

        $request->validate($regras, $feedback, $attributes);

        // Limpar máscaras de telefones e CEP
        $request->merge([
            'zip_code' => preg_replace('/[^0-9]/', '', $request->zip_code ?? ''),
            'mobile_phone' => preg_replace('/[^0-9]/', '', $request->mobile_phone ?? ''),
            'landline_phone' => preg_replace('/[^0-9]/', '', $request->landline_phone ?? ''),
            'accept_receive_messages' => $request->has('accept_receive_messages') ? 1 : 0,
        ]);

        // Atualizar visitante apenas com os campos fillable
        $visitorData = $request->only([
            'name',
            'visit_date',
            'birth_date',
            'gender',
            'marital_status',
            'address',
            'number',
            'neighborhood',
            'complement',
            'zip_code',
            'landline_phone',
            'mobile_phone',
            'profession',
            'education_level',
            'city_id',
            'mail',
            'accept_receive_messages',
            'observations',
        ]);

        $visitor->update($visitorData);

        return redirect()->route('visitors.index')
                         ->with('success', 'Visitante atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitor $visitor)
    {
        //
    }

    /**
     * Exportar visitantes para Excel
     */
    public function export(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate')
        ];

        return Excel::download(
            new VisitorsExport($filters),
            'relacao_visitantes_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Gerar ficha de cadastro de visitante em branco
     */
    public function printBlankForm()
    {
        $church = \App\Models\Church::with('pastor')->first();
        return view('registrations.visitor_blank_form', ['church' => $church]);
    }
}
