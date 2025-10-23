<div class="min-h-screen bg-gray-50 dark:bg-neutral-900">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Budgets</h1>
                    <p class="text-green-100 mt-1">Track spending against your monthly budgets</p>
                </div>
                <div class="flex items-center gap-2 text-white">
                    <button wire:click="previousMonth" class="p-2 rounded-full hover:bg-white/20 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button wire:click="currentMonth" class="px-3 py-1 rounded-lg hover:bg-white/20 transition text-sm font-medium">
                        Today
                    </button>
                    <button wire:click="nextMonth" class="p-2 rounded-full hover:bg-white/20 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mt-3 text-white text-lg font-semibold">
                {{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center justify-between">
                <span>{{ session('message') }}</span>
                <button class="text-green-600 dark:text-green-400 hover:text-green-800">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6 -mt-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Budget</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">ETB  {{ number_format($totalBudget, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Spent</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">ETB {{ number_format($totalSpent, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Remaining</p>
            <p class="text-2xl font-bold @if($totalRemaining < 0) text-red-600 dark:text-red-400 @else text-green-600 dark:text-green-400 @endif">
                ETB  {{ number_format($totalRemaining, 2) }}
            </p>
        </div>
    </div>

    <!-- Overall Progress -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overall Budget Used</span>
                <span class="text-sm font-bold @if($overallPercentage > 100) text-red-600 dark:text-red-400 @else text-gray-900 dark:text-gray-100 @endif">
                    {{ $overallPercentage }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                <div class="h-3 rounded-full @if($overallPercentage > 100) bg-red-500 @else bg-green-500 @endif"
                     style="width: {{ min($overallPercentage, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Budget List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-12">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Category Budgets</h3>
            </div>

            @if($budgets->count())
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($budgets as $budget)
                        <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition" wire:key="budget-{{ $budget->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                         style="background-color: {{ $budget->category->color }}20;">
                                        <div class="w-5 h-5 rounded-full" style="background-color: {{ $budget->category->color }};"></div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $budget->category->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            ETB  {{ number_format($budget->spent, 2) }} / ETB  {{ number_format($budget->amount, 2) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold @if($budget->is_over) text-red-600 dark:text-red-400 @else text-gray-700 dark:text-gray-300 @endif">
                                        {{ $budget->percentage }}%
                                    </span>
                                    <button wire:click="deleteBudget({{ $budget->id }})"
                                            wire:confirm="Are you sure you want to delete this budget?"
                                            class="p-2 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="h-2 rounded-full @if($budget->is_over) bg-red-500 @elseif($budget->percentage >= 80) bg-yellow-500 @else bg-green-500 @endif"
                                         style="width: {{ min($budget->percentage, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    No budgets set for this month.
                </div>
            @endif
        </div>
    </div>
</div>