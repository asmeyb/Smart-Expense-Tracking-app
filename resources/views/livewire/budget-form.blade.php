<div class="min-h-screen bg-gray-50 dark:bg-neutral-900">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $isEdit ? 'Edit Budget' : 'Create Budget' }}</h1>
                <p class="text-green-100 mt-1">Set monthly spending limits for your categories</p>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Budget Amount <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" id="amount" wire:model="amount"
                           class="w-full dark:bg-gray-700 dark:text-gray-100 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                           placeholder="0.00">
                    @error('amount')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="categoryId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category (optional)
                    </label>
                    <select id="categoryId" wire:model="categoryId"
                            class="w-full dark:bg-gray-700 dark:text-gray-100 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">-- All Categories --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('categoryId')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Month & Year -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Month <span class="text-red-500">*</span>
                        </label>
                        <select id="month" wire:model="month"
                                class="w-full dark:bg-gray-700 dark:text-gray-100 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                            @foreach($months as $m)
                                <option value="{{ $m['value'] }}">{{ $m['name'] }}</option>
                            @endforeach
                        </select>
                        @error('month')
                            <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Year <span class="text-red-500">*</span>
                        </label>
                        <select id="year" wire:model="year"
                                class="w-full dark:bg-gray-700 dark:text-gray-100 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                        @error('year')
                            <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('budgets.index') }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        {{ $isEdit ? 'Update Budget' : 'Create Budget' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>