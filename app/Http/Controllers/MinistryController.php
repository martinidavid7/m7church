<?php

namespace App\Http\Controllers;

use App\Models\Ministry;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Exception;


class MinistryController extends Controller
{
    private const ADMIN_ROLES = ['Admin', 'Pastor Presidente', 'Pastor Auxiliar', 'Secretaria'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ministries = Ministry::with('leaders')->paginate(15);
        return view('ministries.list', ['ministries' => $ministries]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('ministries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('ministries/logos', 'public');
                $validated['logo'] = 'storage/' . $logoPath;
            }

            Ministry::create($validated);

            return redirect()->route('ministries.index')
                ->with('success', 'Ministério criado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar ministério: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource. Acessível à administração, líderes e membros do ministério.
     */
    public function show(Ministry $ministry): View
    {
        $person = $this->currentPerson();

        $isLeader = $person && $person->isLeaderOf($ministry->id);
        $isMember = $person && $person->ministries()->where('ministries.id', $ministry->id)->exists();

        if (!auth()->user()->hasAnyRole(self::ADMIN_ROLES) && !$isLeader && !$isMember) {
            throw new AccessDeniedHttpException('Você não tem acesso a este ministério.');
        }

        $ministry->load(['leaders', 'members']);

        return view('ministries.show', compact('ministry', 'isLeader'));
    }

    /**
     * Show the form for editing the specified resource. Líderes podem editar seu próprio ministério.
     */
    public function edit(Ministry $ministry): View
    {
        $this->authorizeManage($ministry);

        return view('ministries.edit', compact('ministry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ministry $ministry): RedirectResponse
    {
        $this->authorizeManage($ministry);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            if ($request->hasFile('logo')) {
                // Remove o logo antigo se existir
                if ($ministry->logo && file_exists(public_path($ministry->logo))) {
                    unlink(public_path($ministry->logo));
                }

                $logoPath = $request->file('logo')->store('ministries/logos', 'public');
                $validated['logo'] = 'storage/' . $logoPath;
            }

            $ministry->update($validated);

            return redirect()->route('ministries.index')
                ->with('success', 'Ministério atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar ministério: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ministry $ministry)
    {
        //
    }

    private function currentPerson(): ?Person
    {
        return Person::where('user_id', auth()->id())->first();
    }

    private function authorizeManage(Ministry $ministry): void
    {
        if (auth()->user()->hasAnyRole(self::ADMIN_ROLES)) {
            return;
        }

        $person = $this->currentPerson();

        if ($person && $person->isLeaderOf($ministry->id)) {
            return;
        }

        throw new AccessDeniedHttpException('Você não tem permissão para editar este ministério.');
    }
}
