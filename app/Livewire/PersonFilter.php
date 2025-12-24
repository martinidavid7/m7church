<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Person;
use App\Models\Church;

class PersonFilter extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $persons = Person::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function($query) {
                $query->where('active', $this->statusFilter);
            })
            ->orderBy('name')
            ->paginate(15);

        $churches = Church::orderBy('church_name')->get();

        return view('livewire.person-filter', [
            'persons' => $persons,
            'churches' => $churches
        ]);
    }
}
