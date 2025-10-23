<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Categories extends Component
{
    public $name = '';
    public $color = '#3B82F6';
    public $icon = "";
    public $editingId = null;
    public $isEditing = false;

    public $colors = [
            '#FF0000', // Red
            '#00FF00', // Lime
            '#0000FF', // Blue
            '#FFFF00', // Yellow
            '#00FFFF', // Cyan/Aqua
            '#FF00FF', // Magenta/Fuchsia
            '#C0C0C0', // Silver
            '#808080', // Gray
            '#800000', // Maroon
            '#808000', // Olive
            '#008000', // Green
            '#800080', // Purple
            '#008080', // Teal
            '#000080', // Navy
            '#FFA500', // Orange
            '#FFC0CB', // Pink
            '#4B0082'  // Indigo
            ];
    #[Computed]
    public function categories()
    {
        return Category::withCount('expenses')->where('user_id', auth()->id())
                        ->orderBy('name')->get();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . ($this->editingId ? : 'NULL') . ',id,user_id,' . auth()->id(),
            'color' => 'required|string|size:7',
            'icon' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'name.required' => 'The category name is required.',
        'name.unique' => 'You already have a category with this name.',
        'color.required' => 'Please select a color for the category.',
    ];

    public function edit($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        if($category->user_id !== auth()->id())
        {
            abort(403);
        }

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->color = $category->color;
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
         $this->reset(['name', 'color', 'icon', 'editingId', 'isEditing']);
         $this->color = "#3B82F6";
    }

    public function save()
    {
        $this->validate();

        if($this->isEditing && $this->editingId)
        {
            $category = Category::findOrFail($this->editingId);

            if($category->user_id !== auth()->user()->id)
            {
                abort(403);
            }

            $category->update([
                'name' => $this->name,
                'color' => $this->color,
                'icon' => $this->icon
            ]);

            session()->flash('message', 'Category Updated Successfully');
        } else{
            // Creating
            Category::create([
                'user_id' => auth()->id(),
                'name' => $this->name,
                'color' => $this->color,
                'icon' => $this->icon,
            ]);

            session()->flash('message', 'Category created successfully!');

        }

        $this->reset(['name', 'color', 'icon', 'editingId', 'isEditing']);

    }

    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        if($category->user_id !== auth()->user()->id)
        {
            abort(403);
        }

        // Check if a category has expenses
        if($category->expenses()->count > 0)
        {
            session()->flash('error', 'Cannot Delete Category with existing expenses');
            return;
        }

        $category->delete();
        session()->flash('message', 'Category Deleted Successfully');
    }
        
    public function render()
    {
        return view('livewire.categories', ['categories' => $this->categories()]);
    }
}
