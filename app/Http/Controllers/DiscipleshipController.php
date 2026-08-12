<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDiscipulado;
use App\Models\Discipleship;
use App\Models\Person;
use App\Models\Visitor;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DiscipleshipController extends Controller
{
    use AuthorizesDiscipulado;

    private const TARGET_CLASSES = [
        'person' => Person::class,
        'visitor' => Visitor::class,
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorizeDiscipuladoView();

        $discipleships = $this->scopeDiscipleshipsToCurrentUser(
            Discipleship::with(['discipulador', 'discipulado'])->active()
        )
            ->latest('started_at')
            ->paginate(15);

        $canManage = $this->canManageDiscipulado();

        return view('discipleships.list', compact('discipleships', 'canManage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorizeDiscipuladoManage();

        $isAdmin = $this->isMinistryAdmin();
        $people = Person::orderBy('name')->get();
        $visitors = Visitor::orderBy('name')->get();

        return view('discipleships.create', compact('people', 'visitors', 'isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeDiscipuladoManage();

        $isAdmin = $this->isMinistryAdmin();

        $validated = $request->validate([
            'discipulador_id' => [$isAdmin ? 'required' : 'nullable', 'exists:persons,id'],
            'discipulado_type' => ['required', Rule::in(array_keys(self::TARGET_CLASSES))],
            'discipulado_id' => ['required', 'integer'],
            'started_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        if (!$isAdmin) {
            $validated['discipulador_id'] = $this->currentPerson()?->id;
        }

        if (!$validated['discipulador_id']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Não foi possível identificar o discipulador.');
        }

        $targetClass = self::TARGET_CLASSES[$validated['discipulado_type']];

        try {
            $targetClass::findOrFail($validated['discipulado_id']);

            Discipleship::where('discipulado_type', $validated['discipulado_type'])
                ->where('discipulado_id', $validated['discipulado_id'])
                ->active()
                ->update(['status' => 'ended', 'ended_at' => now()]);

            Discipleship::create([
                ...$validated,
                'status' => 'active',
            ]);

            return redirect()->route('discipleships.index')
                ->with('success', 'Discipulado registrado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao registrar discipulado: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource, with target details and notes history.
     */
    public function show(Discipleship $discipleship): View
    {
        $this->authorizeDiscipleshipAccess($discipleship);

        $discipleship->load(['discipulador', 'discipulado', 'notesHistory.author']);

        return view('discipleships.show', compact('discipleship'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discipleship $discipleship): View
    {
        $this->authorizeDiscipleshipAccess($discipleship);

        $discipleship->load(['discipulador', 'discipulado']);

        return view('discipleships.edit', compact('discipleship'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discipleship $discipleship): RedirectResponse
    {
        $this->authorizeDiscipleshipAccess($discipleship);

        $validated = $request->validate([
            'started_at' => ['required', 'date'],
        ]);

        try {
            $discipleship->update($validated);

            return redirect()->route('discipleships.index')
                ->with('success', 'Discipulado atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar discipulado: ' . $e->getMessage());
        }
    }

    /**
     * Store a new note in the discipleship's history.
     */
    public function storeNote(Request $request, Discipleship $discipleship): RedirectResponse
    {
        $this->authorizeDiscipleshipAccess($discipleship);

        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $discipleship->notesHistory()->create([
            'author_id' => $this->currentPerson()?->id,
            'body' => $validated['body'],
        ]);

        return redirect()->route('discipleships.show', $discipleship)
            ->with('success', 'Observação registrada.');
    }

    /**
     * Mark the discipleship as ended.
     */
    public function end(Discipleship $discipleship): RedirectResponse
    {
        $this->authorizeDiscipleshipAccess($discipleship);

        $discipleship->update(['status' => 'ended', 'ended_at' => now()]);

        return redirect()->route('discipleships.index')
            ->with('success', 'Discipulado encerrado.');
    }
}
