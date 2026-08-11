<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Exception;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $services = Service::with('serviceType')->paginate(15);
        return view('services.list', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $serviceTypes = ServiceType::orderBy('service_type')->get();
        return view('services.create', compact('serviceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'day_of_week' => ['required', Rule::in(Service::DAYS_OF_WEEK)],
            'time' => 'required|date_format:H:i',
            'service_type_id' => 'required|exists:service_type,id',
        ]);

        try {
            Service::create($validated);

            return redirect()->route('services.index')
                ->with('success', 'Reunião criada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar reunião: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): View
    {
        $serviceTypes = ServiceType::orderBy('service_type')->get();
        return view('services.edit', compact('service', 'serviceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'day_of_week' => ['required', Rule::in(Service::DAYS_OF_WEEK)],
            'time' => 'required|date_format:H:i',
            'service_type_id' => 'required|exists:service_type,id',
        ]);

        try {
            $service->update($validated);

            return redirect()->route('services.index')
                ->with('success', 'Reunião atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar reunião: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): RedirectResponse
    {
        try {
            $service->delete();

            return redirect()->route('services.index')
                ->with('success', 'Reunião excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir reunião: ' . $e->getMessage());
        }
    }
}
