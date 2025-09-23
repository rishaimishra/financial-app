<x-filament::page>
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::card>
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-arrow-trending-up class="h-6 w-6 text-success-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Income</p>
                            <p class="text-lg font-semibold text-success-600">
                                ₹{{ number_format($this->getTotalIncome(), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-arrow-trending-down class="h-6 w-6 text-danger-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Expenses</p>
                            <p class="text-lg font-semibold text-danger-600">
                                ₹{{ number_format($this->getTotalExpenses(), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-chart-bar class="h-6 w-6 text-primary-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Profit/Loss</p>
                            <p class="text-lg font-semibold {{ $this->getProfitLoss() >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                                ₹{{ number_format(abs($this->getProfitLoss()), 2) }}
                                {{ $this->getProfitLoss() >= 0 ? 'Profit' : 'Loss' }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-document-text class="h-6 w-6 text-gray-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Transactions</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $this->getTransactionCount() }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-filament::card>
        </div>

        <!-- Business Details -->
        <x-filament::card>
            <x-filament::card.header>
                <x-filament::card.heading>
                    Business Information
                </x-filament::card.heading>
            </x-filament::card.header>

            <x-filament::card.content>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Business Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $this->getRecord()->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Industry</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $this->getRecord()->industry ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Owner</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $this->getRecord()->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Established Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $this->getRecord()->created_date?->format('M d, Y') ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </x-filament::card.content>
        </x-filament::card>

        <!-- Recent Transactions -->
        <x-filament::card>
            <x-filament::card.header>
                <x-filament::card.heading>
                    Recent Transactions (Last 10)
                </x-filament::card.heading>
            </x-filament::card.header>

            <x-filament::card.content>
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Category</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($this->getRecentTransactions() as $transaction)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $transaction['type'] === 'income' ? 'bg-success-50 text-success-700 ring-1 ring-inset ring-success-600/20' : 'bg-danger-50 text-danger-700 ring-1 ring-inset ring-danger-600/20' }}">
                                            {{ ucfirst($transaction['type']) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm {{ $transaction['type'] === 'income' ? 'text-success-600' : 'text-danger-600' }}">
                                        ₹{{ number_format($transaction['amount'], 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ $transaction['description'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ $transaction['category'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ $transaction['date']->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-sm text-gray-500 text-center">No transactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::card.content>
        </x-filament::card>
    </div>
</x-filament::page>