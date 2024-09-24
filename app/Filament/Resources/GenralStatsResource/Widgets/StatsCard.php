<?php

namespace App\Filament\Resources\GenralStatsResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Product;
class StatsCard extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(label: 'Clients', value: Client::count()),

            // Number of Suppliers
            Stat::make(label: 'Suppliers', value: Supplier::count()),

            // Unpaid Invoices Count
            Stat::make(label: 'Unpaid Invoices', value: Invoice::where('status', '!=', 'completed')->count()),

            // Number of Products
            Stat::make(label: 'Products', value: Product::count()),

            // Total Amount Unpaid
            Stat::make(label: 'Total Amount Unpaid', value: Invoice::where('status', '!=', 'completed')->sum('total_amount'))
                ->suffix('$') // Add a suffix for currency formatting
                ->formatStateUsing
        ];
    }
}
