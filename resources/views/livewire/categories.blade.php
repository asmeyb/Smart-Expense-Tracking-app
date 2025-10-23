<div class="min-h-screen bg-gray-50 dark:bg-neutral-900">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Categories</h1>
                <p class="text-green-100 mt-1">Organize Your Expenses With custom categories</p>
            </div>
        </div>
    </div>

    {{-- Messages / Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center justify-between"
                role="alert">
                <span>{{ session('message') }}</span>
                <button class="text-green-600 dark:text-green-400 hover:text-green-800">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center justify-between"
                role="alert">
                <span>{{ session('error') }}</span>
                <button class="text-red-600 dark:text-red-400 hover:text-red-800">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Create/Edit Categories form --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">
                    {{ $isEditing ? 'Edit Category' : 'Create Category' }}
                </h3>
                <form wire:submit="save" class="space-y-4">
                    <div class="form-group">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Category Name<span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" wire:model="name"
                            class="w-full dark:bg-gray-700 dark:text-gray-100 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                        @error('name')
                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Color<span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-6 gap-2">
                            @foreach($colors as $colorOption)
                                <button type="button" wire:click="$set('color', '{{ $colorOption }}')"
                                    class="w-10 h-10 rounded-lg transition transform hover:scale-110 {{ $color === $colorOption ? 'ring-4 ring-offset-2 ring-gray-400 dark:ring-gray-500' : '' }}"
                                    style="background-color: {{ $colorOption }};">
                                </button>
                            @endforeach
                        </div>
                        @error('color')
                            <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Preview --}}
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium"
                            style="background-color: {{ $color }}20; color: {{ $color }};">
                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $color }};"></span>
                            {{ $name ?: 'Category Name' }}
                        </div>
                    </div>

                    {{-- Form Action --}}
                    <div class="flex gap-2">
                        @if($isEditing)
                            <button type="button" wire:click="cancelEdit"
                                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-200 font-semibold">
                                Cancel
                            </button>
                        @endif
                        <button type="submit"
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            {{ $isEditing ? 'Update Category' : 'Create Category' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Categories List --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Your Categories</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $categories->count() }} Categories</p>
                </div>

                @if($categories->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($categories as $category)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition" wire:key="category-{{ $category->id }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                                           style="background-color: {{ $category->color }}20;">
                                           <div class="w-6 h-6 rounded-full" 
                                                style="background-color: {{ $category->color }};">
                                           </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $category->name }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $category->expenses_count }} {{ Str::plural('expense', $category->expenses_count) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button wire:click="edit({{ $category->id }})"
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>

                                        @if($category->expenses_count === 0)
                                            <button wire:click="delete({{ $category->id }})"
                                                wire:confirm="Are you sure you want to delete this category?"
                                                class="p-2 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        @else
                                            <div class="p-2 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>