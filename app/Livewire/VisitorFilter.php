<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visitor;

class VisitorFilter extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate = '';
    public $endDate = '';

    protected $queryString = ['search', 'startDate', 'endDate'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $visitors = Visitor::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->startDate, function($query) {
                $query->where('visit_date', '>=', $this->startDate);
            })
            ->when($this->endDate, function($query) {
                $query->where('visit_date', '<=', $this->endDate);
            })
            ->orderBy('visit_date', 'desc')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.visitor-filter', [
            'visitors' => $visitors
        ]);
    }
}
