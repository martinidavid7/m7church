<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesMinistryAccess;
use App\Models\Ministry;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class ScheduleController extends Controller
{
    use AuthorizesMinistryAccess;

    /**
     * Calendário mensal de escalas do ministério.
     */
    public function index(Ministry $ministry): View
    {
        $this->authorizeMinistryView($ministry);

        $canManage = $this->isMinistryAdmin() || $this->isMinistryLeader($ministry);

        return view('schedules.calendar', [
            'ministry' => $ministry,
            'isLeader' => $canManage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Ministry $ministry): View
    {
        $this->authorizeMinistryManage($ministry);

        $services = \App\Models\Service::orderBy('day_of_week')->orderBy('time')->get();
        $people = $ministry->people()->orderBy('name')->get();
        $date = $request->input('date');

        return view('schedules.create', compact('ministry', 'services', 'people', 'date'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Ministry $ministry): RedirectResponse
    {
        $this->authorizeMinistryManage($ministry);

        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'title' => 'required_without:service_id|nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'people' => 'nullable|array',
            'people.*' => 'exists:persons,id',
            'function' => 'nullable|array',
        ]);

        try {
            $schedule = Schedule::create([
                'ministry_id' => $ministry->id,
                'service_id' => $validated['service_id'] ?? null,
                'title' => $validated['service_id'] ? null : ($validated['title'] ?? null),
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncAssignments($schedule, $request);

            return redirect()->route('ministries.schedules.index', $ministry)
                ->with('success', 'Escala criada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar escala: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ministry $ministry, Schedule $schedule): View
    {
        $this->authorizeMinistryManage($ministry);

        $services = \App\Models\Service::orderBy('day_of_week')->orderBy('time')->get();
        $people = $ministry->people()->orderBy('name')->get();
        $schedule->load('assignments');

        $currentAssignments = $schedule->assignments->pluck('function', 'person_id');

        return view('schedules.edit', compact('ministry', 'schedule', 'services', 'people', 'currentAssignments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ministry $ministry, Schedule $schedule): RedirectResponse
    {
        $this->authorizeMinistryManage($ministry);

        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'title' => 'required_without:service_id|nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'people' => 'nullable|array',
            'people.*' => 'exists:persons,id',
            'function' => 'nullable|array',
        ]);

        try {
            $schedule->update([
                'service_id' => $validated['service_id'] ?? null,
                'title' => $validated['service_id'] ? null : ($validated['title'] ?? null),
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncAssignments($schedule, $request);

            return redirect()->route('ministries.schedules.index', $ministry)
                ->with('success', 'Escala atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar escala: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ministry $ministry, Schedule $schedule): RedirectResponse
    {
        $this->authorizeMinistryManage($ministry);

        $schedule->delete();

        return redirect()->route('ministries.schedules.index', $ministry)
            ->with('success', 'Escala removida com sucesso!');
    }

    private function syncAssignments(Schedule $schedule, Request $request): void
    {
        $selectedIds = $request->input('people', []);
        $functionsById = $request->input('function', []);

        $sync = [];
        foreach ($selectedIds as $personId) {
            $sync[$personId] = ['function' => $functionsById[$personId] ?? null];
        }

        $schedule->people()->sync($sync);
    }
}
