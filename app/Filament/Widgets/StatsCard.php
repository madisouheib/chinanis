<?php

namespace App\Filament\Widgets;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Client;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StatsCard extends BaseWidget
{

    
    protected function getStats(): array
    {
        return [
            // Number of Clients
            Stat::make(label: 'Clients', value: Client::count())
                ->icon('heroicon-o-user'), // User icon

            // Number of Suppliers
            Stat::make(label: 'Suppliers', value: Supplier::count())
                ->icon('heroicon-o-truck'), // Truck icon

            // Unpaid Invoices Count
            Stat::make(label: 'Unpaid Invoices', value: Invoice::where('status', '!=', 'completed')->count())
                ->icon('heroicon-o-chat-bubble-bottom-center-text'), // Document report icon

            // Number of Products
            Stat::make(label: 'Products', value: Product::count())
                ->icon('heroicon-o-circle-stack'), // Collection icon

            // Total Amount Unpaid
            Stat::make(
                label: 'Total Amount Unpaid',
                value: '$' . number_format(Invoice::where('status', '!=', 'completed')->sum('total_amount'), 2)
            )
            ->icon('heroicon-o-banknotes'), // Cash icon (use appropriate icon)
            ];
    }
}
