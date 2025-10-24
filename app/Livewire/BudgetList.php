<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;

class BudgetList extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $showCreateModel = true;
    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
    }

    #[Computed]
    public function budgets()
    {
        return Budget::with('category')
            ->where('user_id', auth()->id())
            ->where('month', $this->selectedMonth)
            ->where('year', $this->selectedYear)
            ->get()
            ->map(function ($budget) {
                $budget->spent = $budget->getSpentAmount();
                $budget->remaining = $budget->getRemainingAmount();
                $budget->percentage = $budget->getPercentUsed();
                $budget->is_over = $budget->isOverBudget();
                return $budget;   // <-- add this
            });
    }

    #[Computed]
    public function totalBudget()
    {
        return $this->budgets()->sum('amount');
    }
    #[Computed]
    public function totalSpent()
    {
        return $this->budgets->sum('spent');
    }

    #[Computed]
    public function totalRemaining()
    {
        return $this->budgets->sum('remaining');
    }

    #[Computed]
    public function overallPercentage()
    {
        if ($this->totalBudget == 0) {
            return 0;
        }

        return round(($this->totalSpent / $this->totalBudget) * 100, 1);
    }

    #[Computed]
    public function categories()
    {
        return Category::where('user_id', Auth::user()->id)
            ->orderBy('name')->get();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth)->addMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }

    public function currentMonth()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
    }

    public function deleteBudget($id)
    {
        $budget = Budget::findOrFail($id);

        if ($budget->user_id !== Auth::user()->id) {
            abort(403);
        }

        $budget->delete();

        session()->flash('message', 'Budget Deleted Successfully');
    }
    public function render()
    {
        return view(
            'livewire.budget-list',
            [
                'budgets' => $this->budgets,
                'totalBudget' => $this->totalBudget,
                'totalSpent' => $this->totalSpent,
                'totalRemaining' => $this->totalRemaining,
                'overallPercentage' => $this->overallPercentage,
                'categories' => $this->categories,
            ]
        );
    }
}