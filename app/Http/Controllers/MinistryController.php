<?php

namespace App\Http\Controllers;

use App\Models\Ministry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Exception;


class MinistryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ministries = Ministry::with('leader')->paginate(15);
        return view('ministries.list', ['ministries' => $ministries]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('ministries.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
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
     * Display the specified resource.
     */
    public function show(Ministry $ministry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $ministry = Ministry::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('ministries.edit', compact('ministry', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $ministry = Ministry::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
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
}
