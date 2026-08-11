<?php

namespace App\Livewire;

use App\Models\Ministry;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Livewire\Component;

class MinistryScheduleCalendar extends Component
{
    public Ministry $ministry;
    public bool $isLeader;
    public int $month;
    public int $year;

    public ?int $addingToScheduleId = null;
    public string $newPersonId = '';
    public string $newPersonFunction = '';

    public function mount(Ministry $ministry, bool $isLeader): void
    {
        $this->ministry = $ministry;
        // $isLeader aqui já representa "pode gerenciar" (líder do ministério, Admin, Pastor ou Secretaria),
        // resolvido pelo ScheduleController antes de renderizar este componente.
        $this->isLeader = $isLeader;
        $this->month = (int) now()->month;
        $this->year = (int) now()->year;
    }

    public function previousMonth(): void
    {
        $reference = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = $reference->month;
        $this->year = $reference->year;
    }

    public function nextMonth(): void
    {
        $reference = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = $reference->month;
        $this->year = $reference->year;
    }

    public function startAdding(int $scheduleId): void
    {
        if (!$this->isLeader) {
            return;
        }

        $this->addingToScheduleId = $scheduleId;
        $this->newPersonId = '';
        $this->newPersonFunction = '';
    }

    public function cancelAdding(): void
    {
        $this->addingToScheduleId = null;
    }

    /**
     * Ao selecionar a pessoa, sugere a função cadastrada no vínculo dela com o ministério.
     */
    public function updatedNewPersonId(): void
    {
        if (!$this->newPersonId) {
            $this->newPersonFunction = '';
            return;
        }

        $link = $this->ministry->people()->where('persons.id', $this->newPersonId)->first();
        $this->newPersonFunction = $link?->pivot->function ?? '';
    }

    public function addPerson(): void
    {
        if (!$this->isLeader || !$this->addingToScheduleId || !$this->newPersonId) {
            return;
        }

        $schedule = Schedule::where('ministry_id', $this->ministry->id)->findOrFail($this->addingToScheduleId);

        $schedule->people()->syncWithoutDetaching([
            $this->newPersonId => ['function' => $this->newPersonFunction ?: null],
        ]);

        $this->addingToScheduleId = null;
        $this->newPersonId = '';
        $this->newPersonFunction = '';
    }

    public function removePerson(int $scheduleId, int $personId): void
    {
        if (!$this->isLeader) {
            return;
        }

        $schedule = Schedule::where('ministry_id', $this->ministry->id)->findOrFail($scheduleId);
        $schedule->people()->detach($personId);
    }

    public function deleteSchedule(int $scheduleId): void
    {
        if (!$this->isLeader) {
            return;
        }

        Schedule::where('ministry_id', $this->ministry->id)->findOrFail($scheduleId)->delete();
    }

    public function render()
    {
        $reference = Carbon::createFromDate($this->year, $this->month, 1);

        $schedules = Schedule::with(['service', 'people'])
            ->where('ministry_id', $this->ministry->id)
            ->whereBetween('date', [$reference->copy()->startOfMonth(), $reference->copy()->endOfMonth()])
            ->orderBy('date')
            ->get()
            ->groupBy(fn (Schedule $schedule) => $schedule->date->toDateString());

        $availablePeople = $this->ministry->people()->orderBy('name')->get();

        return view('livewire.ministry-schedule-calendar', [
            'reference' => $reference,
            'schedules' => $schedules,
            'availablePeople' => $availablePeople,
        ]);
    }
}
