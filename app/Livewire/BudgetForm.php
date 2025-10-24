<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BudgetForm extends Component
{
    public $budgetId, $amount = '', $month, $year, $categoryId = '', $isEdit = false;

    public function rules()
    {
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'categoryId' => 'nullable|exists:categories,id'
        ];

        $uniqueRule =
            'unique|budgets,categoryId, NULL, id, user_id' . auth()->user()->id . ',month' . $this->month . ',year' . $this->year;

        if ($this->isEdit) {
            $uniqueRule = "unique:budgets,category_id,{$this->budgetId},id,user_id," . auth()->id() . ",month,{$this->month},year,{$this->year}";
        } else {
            $uniqueRule = "unique:budgets,category_id,NULL,id,user_id," . auth()->id() . ",month,{$this->month},year,{$this->year}";
        }

        $rules['categoryId'] = $this->categoryId
            ? 'nullable|exists:categories,id|' . $uniqueRule
            : 'nullable|' . $uniqueRule;

        return $rules;
    }

    protected $messages = [
        'amount.required' => 'Please Enter Budget Amount',
        'amount.min' => 'Please Enter Budget Amount > 0',
        'year.required' => 'Please Enter Valid Year',
        'month.required' => 'Please Enter Valid Month',
        'year.min' => 'Please Enter Valid Year',
        'month.min' => 'Please Enter Valid Month',
        'year.max' => 'Please Enter Valid Year',
        'month.max' => 'Please Enter Valid Month',
        'categoryId.unique' => 'You already have a budget for this category in amonth'
    ];

    public function mount($budgetId = null)   // MUST match the route segment name
    {
        $this->budgetId = $budgetId;           // now the property is filled
        $this->isEdit = (bool) $budgetId;

        if ($this->isEdit) {
            $this->loadBudget();
        } else {
            $this->month = now()->month;
            $this->year = now()->year;
        }

        //dd($budgetId, request()->route()->parameters());
    }

    public function loadBudget()
    {
        $budget = Budget::findOrFail($this->budgetId);

        if ($budget->user_id !== auth()->user()->id) {
            abort(403);
        }

        $this->amount = $budget->amount;
        $this->month = $budget->month;
        $this->year = $budget->year;
        $this->categoryId = $budget->category_id;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'amount' => $this->amount,
            'month' => $this->month,
            'year' => $this->year,
            'category_id' => $this->categoryId ?: null, // <-- cast empty to null
        ];

        if ($this->isEdit) {
            $budget = Budget::findOrFail($this->budgetId);

            if ($budget->user_id !== auth()->user()->id) {
                abort(403);
            }

            $budget->update($data);

            session()->flash('message', 'Budget Updated Successfully');
        } else {
            Budget::create($data);
        }

        return redirect()->route('budgets.index');
    }

    #[Computed]
    public function months()
    {
        return collect(range(1, 12))->map(function ($month) {
            return [
                'value' => $month,
                'name' => Carbon::create(null, $month, 1)->format('F')
            ];
        });
    }

    #[Computed]
    public function years()
    {
        $currentYear = now()->year;
        return collect(range($currentYear - 1, $currentYear + 2));
    }


    #[Computed]
    public function categories()
    {
        return Category::where('user_id', auth()->user()->id)
            ->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.budget-form', [
            'categories' => $this->categories,
            'months' => $this->months,
            'years' => $this->years,
        ]);
    }
    public function deleteBudget($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);

        $budget->delete();

        session()->flash('message', 'Budget deleted Successfully');

    }
}
