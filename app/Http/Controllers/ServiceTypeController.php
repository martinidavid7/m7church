<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $serviceTypes = ServiceType::paginate(15);
        return view('service_types.list', ['serviceTypes' => $serviceTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('service_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
        ]);

        try {
            ServiceType::create($validated);

            return redirect()->route('service_type.index')
                ->with('success', 'Tipo de serviço criado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar tipo de serviço: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceType $serviceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceType $serviceType): View
    {
        return view('service_types.edit', compact('serviceType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceType $serviceType): RedirectResponse
    {
        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
        ]);

        try {
            $serviceType->update($validated);

            return redirect()->route('service_type.index')
                ->with('success', 'Tipo de serviço atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar tipo de serviço: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceType $serviceType): RedirectResponse
    {
        try {
            $serviceType->delete();

            return redirect()->route('service_type.index')
                ->with('success', 'Tipo de reunião excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir tipo de reunião: ' . $e->getMessage());
        }
    }
}
