<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Models\City;
use App\Models\UF;
use App\Models\Church;
use App\Models\Ministry;
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

        $query = Person::with('church');
        $query->orderBy('name'); //Ordena por nome


        // Filtro por nome
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->where('name', 'like', "%{$name}%");
        }

        // Filtro por igreja
        if ($request->filled('church_id')) {
            $query->where('church_id', $request->input('church_id'));
        }

        // Filtro por status (ativo/inativo)
        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active')); // boolean() converte '1', 'true', etc.
        }

        $persons = $query->paginate(5);
        $churches = Church::orderBy('church_name')->get();

        return view('registrations.person_list', ['persons' => $persons, 'churches' => $churches]);
    }



    public function create()
    {
        $cities = City::all();
        $uf = UF::all();
        $churches = Church::orderBy('church_name')->get();
        $roles = \Spatie\Permission\Models\Role::all();
        $ministries = Ministry::orderBy('name')->get();
        return view('registrations.person_create', [
            'uf' => $uf,
            'cities' => $cities,
            'churches' => $churches,
            'roles' => $roles,
            'ministries' => $ministries,
        ]);
    }


    public function store(Request $request)
    {
        // Inicia uma transação para garantir que ou ambos são criados, ou nenhum
        DB::beginTransaction();

        try {
            \Log::info('Iniciando cadastro de pessoa', ['dados' => $request->except(['password', 'confirm_password', 'photo'])]);

            //regras de validacao
            $rules = [
                'name' => 'required|string|max:255',
                'birth_date' => 'nullable|date|before:today',
                'gender' => 'nullable|in:M,F',
                'marital_status' => 'nullable|string|max:50',
                'mail' => 'required|email|unique:users,email',
                'mobile_phone' => 'required|string|min:10',
                'landline_phone' => 'nullable|string',
                'profession' => 'nullable|string|max:100',
                'education_level' => 'nullable|string|max:100',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'baptism_date' => 'nullable|date',
                'membership_date' => 'nullable|date',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|same:password',
                'active' => 'required|in:0,1',
                'city_id' => 'nullable|exists:cities,id',
                'church_id' => 'nullable|exists:churches,id',
            ];

            //feedback de validacao
            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'email' => 'O campo :attribute precisa ser um e-mail válido',
                'unique' => 'Este :attribute já está cadastrado no sistema',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres',
                'in' => 'O campo :attribute deve ser uma opção válida',
                'date' => 'O campo :attribute deve ser uma data válida',
                'before' => 'O campo :attribute deve ser uma data anterior a hoje',
                'image' => 'O campo :attribute deve ser uma imagem',
                'mimes' => 'O campo :attribute deve ser do tipo: :values',
                'same' => 'O campo :attribute deve ser igual ao campo senha',
                'exists' => 'O :attribute selecionado não é válido',
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
                'membership_date' => 'data de membresia',
                'password' => 'senha',
                'confirm_password' => 'confirmação de senha',
                'active' => 'status',
                'city_id' => 'cidade',
                'church_id' => 'igreja',
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
                $photo = $request->file('photo');
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/persons/photos'), $photoName);
                $photoPath = 'uploads/persons/photos/' . $photoName;
                \Log::info('Foto carregada', ['path' => $photoPath]);
            }

            // 1. Criar o usuário
            \Log::info('Criando usuário', ['nome' => $request->input('name'), 'email' => $request->input('mail')]);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('mail'),
                'password' => Hash::make($request->input('password')),
            ]);
            \Log::info('Usuário criado', ['user_id' => $user->id]);

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
                'city_id',
                'church_id'
            ]);

            $personData['user_id'] = $user->id;
            if ($photoPath) {
                $personData['photo'] = $photoPath;
            }

            // 3. Criar a pessoa usando atribuição em massa
            \Log::info('Criando pessoa', ['dados' => $personData]);
            $person = Person::create($personData);
            \Log::info('Pessoa criada', ['person_id' => $person->id]);

            // 4. Associar roles ao usuário se foram selecionados (usando Spatie)
            if ($request->has('roles') && is_array($request->roles)) {
                \Log::info('Associando roles', ['roles' => $request->roles]);
                $user->syncRoles($request->roles);
            }

            // 5. Associar ministérios (líder/membro)
            $this->syncPersonMinistries($person, $request);

            DB::commit();
            \Log::info('Cadastro de pessoa concluído com sucesso');

            return redirect()->route('person.index')->with('success', 'Membro e usuário cadastrados com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Erro de validação ao cadastrar pessoa', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            \Log::error('Erro de banco de dados ao cadastrar pessoa', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);

            // Verificar se é erro de email duplicado
            if ($e->errorInfo[1] == 1062 && str_contains($e->getMessage(), 'users_email_unique')) {
                return redirect()->route('person.create')
                    ->withInput()
                    ->with('error', 'Este e-mail já está cadastrado no sistema. Por favor, use um e-mail diferente.');
            }

            // Outros erros de banco de dados
            return redirect()->route('person.create')
                ->withInput()
                ->with('error', 'Erro ao realizar o cadastro! Erro de banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Erro geral ao cadastrar pessoa', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('person.create')
                ->withInput()
                ->with('error', 'Erro ao realizar o cadastro! ' . $e->getMessage());
        }
    }


    public function edit(string $id)
    {

        try {


            $person = Person::findOrFail($id);
            $cities = City::all();
            $uf = UF::all();
            $churches = Church::orderBy('church_name')->get();
            $roles = \Spatie\Permission\Models\Role::all();
            $ministries = Ministry::orderBy('name')->get();

            // Verificar se a pessoa tem user_id e buscar roles (usando Spatie)
            $personRoles = [];
            if ($person->user_id) {
                $user = User::find($person->user_id);
                if ($user) {
                    $personRoles = $user->roles->pluck('name')->toArray();
                }
            }

            // Ministérios já vinculados: [ministry_id => ['role' => ..., 'function' => ...]]
            $personMinistries = $person->ministries()->get()
                ->mapWithKeys(fn ($ministry) => [
                    $ministry->id => [
                        'role' => $ministry->pivot->role,
                        'function' => $ministry->pivot->function,
                    ],
                ])
                ->toArray();

            return view('registrations.person', [
                'person' => $person,
                'cities' => $cities,
                'uf' => $uf,
                'churches' => $churches,
                'roles' => $roles,
                'personRoles' => $personRoles,
                'ministries' => $ministries,
                'personMinistries' => $personMinistries,
            ]);
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
                'password' => 'nullable|min:6',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'email' => 'O campo :attribute precisa ser um e-mail válido',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres',
                'in' => 'O campo :attribute deve ser M ou F',
                'date' => 'O campo :attribute deve ser uma data válida',
                'image' => 'O campo :attribute deve ser uma imagem',
                'mimes' => 'O campo :attribute deve ser do tipo: :values',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
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
                'password' => 'senha',
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
                'city_id',
                'church_id'
            ]);

            // Upload da foto se houver
            if ($request->hasFile('photo')) {
                // Deletar foto antiga se existir
                if ($person->photo && file_exists(public_path($person->photo))) {
                    unlink(public_path($person->photo));
                }
                $photo = $request->file('photo');
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/persons/photos'), $photoName);
                $personData['photo'] = 'uploads/persons/photos/' . $photoName;
            }

            $person->update($personData);

            // Atualizar usuário relacionado
            if ($person->user_id) {
                $user = User::find($person->user_id);
                if ($user) {
                    $user->name = $request->input('name');
                    $user->email = $request->input('mail');
                    $user->active = $request->input('active', 1);

                    // Atualizar senha se foi fornecida
                    if ($request->filled('password')) {
                        $user->password = Hash::make($request->input('password'));
                    }

                    $user->save();

                    // Atualizar roles do usuário (usando Spatie)
                    if ($request->has('roles') && is_array($request->roles)) {
                        $user->syncRoles($request->roles);
                    } else {
                        $user->syncRoles([]);
                    }
                }
            }

            $this->syncPersonMinistries($person, $request);

            return redirect()->route('person.index')->with('success', 'Dados atualizados com sucesso!');
        } catch (\Exception $e) {
            // Log do erro para debug
            \Log::error('Erro ao atualizar pessoa: ' . $e->getMessage());

            return redirect()->route('person.edit', ['id' => $id])
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o membro: ' . $e->getMessage());
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
     * Editar próprio perfil
     */
    public function editMyProfile()
    {
        $user = auth()->user();
        $person = Person::where('user_id', $user->id)->first();

        // Se o usuário não tem um registro de pessoa vinculado, criar um básico
        if (!$person) {
            $person = Person::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'mail' => $user->email,
                'active' => 1
            ]);
        }

        $cities = City::all();
        $uf = UF::all();
        $churches = Church::orderBy('church_name')->get();

        // Usuário não pode alterar os próprios roles
        return view('registrations.person_my_profile', [
            'person' => $person,
            'cities' => $cities,
            'uf' => $uf,
            'churches' => $churches
        ]);
    }

    /**
     * Atualizar próprio perfil
     */
    public function updateMyProfile(Request $request)
    {
        try {
            $user = auth()->user();
            $person = Person::where('user_id', $user->id)->first();

            // Se o usuário não tem um registro de pessoa vinculado, criar um básico
            if (!$person) {
                $person = Person::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'mail' => $user->email,
                    'active' => 1
                ]);
            }

            // Regras de validação
            $rules = [
                'name' => 'required|string|max:255',
                'birth_date' => 'nullable|date|before:today',
                'gender' => 'nullable|in:M,F',
                'marital_status' => 'nullable|string|max:50',
                'mail' => 'required|email',
                'mobile_phone' => 'nullable|string',
                'landline_phone' => 'nullable|string',
                'profession' => 'nullable|string|max:100',
                'education_level' => 'nullable|string|max:100',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'baptism_date' => 'nullable|date',
                'membership_date' => 'nullable|date',
                'password' => 'nullable|string|min:6',
                'confirm_password' => 'nullable|same:password',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'email' => 'O campo :attribute precisa ser um e-mail válido',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres',
                'in' => 'O campo :attribute deve ser uma opção válida',
                'date' => 'O campo :attribute deve ser uma data válida',
                'before' => 'O campo :attribute deve ser uma data anterior a hoje',
                'image' => 'O campo :attribute deve ser uma imagem',
                'mimes' => 'O campo :attribute deve ser do tipo: :values',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
                'same' => 'O campo :attribute deve ser igual ao campo senha',
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
                'membership_date' => 'data de membresia',
                'password' => 'senha',
                'confirm_password' => 'confirmação de senha',
            ];

            $request->validate($rules, $feedback, $attributes);

            // Limpar máscaras
            $request->merge([
                'zip_code' => preg_replace('/[^0-9]/', '', $request->zip_code ?? ''),
                'mobile_phone' => preg_replace('/[^0-9]/', '', $request->mobile_phone ?? ''),
                'landline_phone' => preg_replace('/[^0-9]/', '', $request->landline_phone ?? ''),
            ]);

            // Preparar dados
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
                'observations',
                'city_id',
                'church_id'
            ]);

            // Upload da foto
            if ($request->hasFile('photo')) {
                if ($person->photo && file_exists(public_path($person->photo))) {
                    unlink(public_path($person->photo));
                }
                $photo = $request->file('photo');
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/persons/photos'), $photoName);
                $personData['photo'] = 'uploads/persons/photos/' . $photoName;
            }

            $person->update($personData);

            // Atualizar usuário
            $user->name = $request->input('name');
            $user->email = $request->input('mail');

            // Atualizar senha se foi fornecida
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            return redirect()->route('person.my-profile')->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar perfil: ' . $e->getMessage());
            return redirect()->route('person.my-profile')
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o perfil.');
        }
    }

    /**
     * Gerar ficha de cadastro em branco
     */
    public function printBlankForm()
    {
        $church = \App\Models\Church::with('pastor')->first();
        return view('registrations.person_blank_form', ['church' => $church]);
    }

    /**
     * Gerar ficha de cadastro preenchida para impressão
     */
    public function print($id)
    {
        $person = Person::with(['church', 'city.uf'])->findOrFail($id);

        // Buscar cargos/roles do usuário vinculado
        $rolesString = '';
        if ($person->user_id) {
            $user = User::find($person->user_id);
            if ($user) {
                $rolesString = $user->roles->pluck('name')->implode(', ');
            }
        }

        return view('registrations.person_print', compact('person', 'rolesString'));
    }

    /**
     * Sincroniza os vínculos de ministério (líder/membro) de uma pessoa
     * a partir dos campos "ministries[]" e "ministry_role[id]" do formulário.
     */
    private function syncPersonMinistries(Person $person, Request $request): void
    {
        $selectedIds = $request->input('ministries', []);
        $rolesById = $request->input('ministry_role', []);
        $functionsById = $request->input('ministry_function', []);

        $sync = [];
        foreach ($selectedIds as $ministryId) {
            $role = $rolesById[$ministryId] ?? 'membro';
            $sync[$ministryId] = [
                'role' => $role === 'lider' ? 'lider' : 'membro',
                'function' => $functionsById[$ministryId] ?? null,
            ];
        }

        $person->ministries()->sync($sync);
    }
}
