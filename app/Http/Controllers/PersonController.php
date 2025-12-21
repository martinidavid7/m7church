<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Models\City;
use App\Models\UF;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Exports\PersonsExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class PersonController extends Controller
{


    public function index(Request $request): View
    {

        $query = Person::query();
        $query->orderBy('name'); //Ordena por nome


        // Filtro por nome
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->where('name', 'like', "%{$name}%");
        }

        // Filtro por status (ativo/inativo)
        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active')); // boolean() converte '1', 'true', etc.
        }

        $persons = $query->paginate(15);

        return view('registrations.person_list', ['persons' => $persons]);
    }



    public function create()
    {
        $cities = City::all();
        $uf = UF::all();
        return view('registrations.person_create', ['uf' => $uf ,'cities' => $cities]);
    }


    public function store(Request $request)
    {
        // Inicia uma transação para garantir que ou ambos são criados, ou nenhum
        try {


            //regras de validacao
            $rules = [
                'name' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:M,F',
                'marital_status' => 'nullable|string|max:50',
                'mail' => 'required|email',
                'mobile_phone' => 'required',
                'landline_phone' => 'nullable',
                'profession' => 'nullable|string|max:100',
                'education_level' => 'nullable|string|max:100',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'baptism_date' => 'nullable|date',
                'password' => 'required|min:6'
            ];

            //feedback de validacao
            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'email' => 'O campo :attribute precisa ser um e-mail válido',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres',
                'in' => 'O campo :attribute deve ser M ou F',
                'date' => 'O campo :attribute deve ser uma data válida',
                'image' => 'O campo :attribute deve ser uma imagem',
                'mimes' => 'O campo :attribute deve ser do tipo: :values',
            ];

            // Personalizar nomes dos atributos
            $attributes = [
                'name' => 'nome',
                'birth_date' => 'data de nascimento',
                'gender' => 'sexo',
                'marital_status' => 'estado civil',
                'mail' => 'e-mail',
                'mobile_phone' => 'telefone celular',
                'landline_phone' => 'telefone fixo',
                'profession' => 'profissão',
                'education_level' => 'escolaridade',
                'photo' => 'foto',
                'baptism_date' => 'data de batismo',
                'password' => 'senha',
            ];

            $request->validate($rules, $feedback, $attributes);

            // Limpar máscaras de CEP e telefones
            $request->merge([
                'zip_code' => preg_replace('/[^0-9]/', '', $request->zip_code ?? ''),
                'mobile_phone' => preg_replace('/[^0-9]/', '', $request->mobile_phone ?? ''),
                'landline_phone' => preg_replace('/[^0-9]/', '', $request->landline_phone ?? ''),
            ]);

            // Upload da foto se existir
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('persons/photos', 'public');
            }

            // 1. Criar o usuário
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('mail'),
                'password' => Hash::make($request->input('password')),
            ]);

            // 2. Preparar os dados para a pessoa (apenas campos que existem na tabela)
            $personData = $request->only([
                'name',
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
                'mail',
                'profession',
                'education_level',
                'baptism_date',
                'membership_date',
                'active',
                'observations',
                'city_id'
            ]);

            $personData['user_id'] = $user->id;
            if ($photoPath) {
                $personData['photo'] = $photoPath;
            }

            // 3. Criar a pessoa usando atribuição em massa
            Person::create($personData);

            return redirect()->route('person.index')->with('success', 'Membro e usuário cadastrados com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Verificar se é erro de email duplicado
            if ($e->errorInfo[1] == 1062 && str_contains($e->getMessage(), 'users_email_unique')) {
                return redirect()->route('person.create')
                    ->withInput()
                    ->with('error', 'Este e-mail já está cadastrado no sistema. Por favor, use um e-mail diferente.');
            }

            // Outros erros de banco de dados
            return redirect()->route('person.create')
                ->withInput()
                ->with('error', 'Erro ao realizar o cadastro! Verifique os dados e tente novamente.');
        } catch (Exception $e) {
            return redirect()->route('person.create')
                ->withInput()
                ->with('error', 'Erro ao realizar o cadastro! Contate o Suporte.');
        }
    }


    public function edit(string $id)
    {

        try {


            $person = Person::findOrFail($id);
            $cities = City::all();
            $uf = UF::all();

            return view('registrations.person', ['person' => $person, 'cities' => $cities, 'uf' => $uf]);
        } catch (Exception $e) {

            return redirect()->route('church')->with('error', 'Pessoa não encontrada ou erro ao carregar.');
        }
    }



    public function update(Request $request, string $id)
    {
        try {

            $person = Person::findOrFail($id);

            // Regras de validação para atualização
            $rules = [
                'name' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:M,F',
                'marital_status' => 'nullable|string|max:50',
                'mail' => 'required|email',
                'mobile_phone' => 'nullable',
                'landline_phone' => 'nullable',
                'profession' => 'nullable|string|max:100',
                'education_level' => 'nullable|string|max:100',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'baptism_date' => 'nullable|date',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'email' => 'O campo :attribute precisa ser um e-mail válido',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres',
                'in' => 'O campo :attribute deve ser M ou F',
                'date' => 'O campo :attribute deve ser uma data válida',
                'image' => 'O campo :attribute deve ser uma imagem',
                'mimes' => 'O campo :attribute deve ser do tipo: :values',
            ];

            $attributes = [
                'name' => 'nome',
                'birth_date' => 'data de nascimento',
                'gender' => 'sexo',
                'marital_status' => 'estado civil',
                'mail' => 'e-mail',
                'mobile_phone' => 'telefone celular',
                'landline_phone' => 'telefone fixo',
                'profession' => 'profissão',
                'education_level' => 'escolaridade',
                'photo' => 'foto',
                'baptism_date' => 'data de batismo',
            ];

            $request->validate($rules, $feedback, $attributes);

            // Limpar máscaras de telefones
            $request->merge([
                'zip_code' => preg_replace('/[^0-9]/', '', $request->zip_code ?? ''),
                'mobile_phone' => preg_replace('/[^0-9]/', '', $request->mobile_phone ?? ''),
                'landline_phone' => preg_replace('/[^0-9]/', '', $request->landline_phone ?? ''),
            ]);

            // Preparar dados para atualização (apenas campos que existem na tabela)
            $personData = $request->only([
                'name',
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
                'mail',
                'profession',
                'education_level',
                'baptism_date',
                'membership_date',
                'active',
                'observations',
                'city_id'
            ]);

            // Upload da foto se houver
            if ($request->hasFile('photo')) {
                // Deletar foto antiga se existir
                if ($person->photo && \Storage::disk('public')->exists($person->photo)) {
                    \Storage::disk('public')->delete($person->photo);
                }
                $personData['photo'] = $request->file('photo')->store('persons/photos', 'public');
            }

            $person->update($personData);

            // Atualizar usuário relacionado
            $user = User::find($person->user_id);
            if ($user) {
                $user->name = $request->input('name');
                $user->email = $request->input('mail');
                $user->active = $request->input('active', 1);
                $user->save();
            }

            return redirect()->route('person.index')->with('success', 'Dados atualizados com sucesso!');
        } catch (\Exception $e) {

            return redirect()->route('person.edit', ['id' => $id])
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o membro.');
        }
    }

    public function getCitiesByUf($uf_id)
    {
        $cities = City::where('uf_id', $uf_id)->get();
        return response()->json($cities);
    }

    /**
     * Exportar membros para Excel
     */
    public function export(Request $request)
    {
        $filters = [
            'name' => $request->input('name'),
            'active' => $request->input('active')
        ];

        return Excel::download(
            new PersonsExport($filters),
            'relacao_membros_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Gerar ficha de cadastro em branco
     */
    public function printBlankForm()
    {
        $church = \App\Models\Church::with('pastor')->first();
        return view('registrations.person_blank_form', ['church' => $church]);
    }
}
