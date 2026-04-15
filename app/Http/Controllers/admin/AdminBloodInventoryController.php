<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodUnit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminBloodInventoryController extends Controller
{
    public function index(): View
    {
        // Sync inventory from BloodUnit table
        $this->syncAllInventory();
        
        $inventory = BloodInventory::orderBy('blood_group')->get();
        
        // Low stock count for sidebar badge
        $lowStockCount = BloodInventory::where('available_units', '<', DB::raw('threshold'))->count();
        
        $stats = [
            'totalUnits'     => $inventory->sum('total_units'),
            'availableUnits' => $inventory->sum('available_units'),
            'lowStock'       => $lowStockCount,
            'groupsBelowThreshold' => $lowStockCount,
        ];

        return view('admin.inventories.index', compact('inventory', 'stats'));
    }
    
    private function syncAllInventory(): void
    {
        $groups = BloodUnit::select('blood_group')->distinct()->pluck('blood_group');
        foreach ($groups as $group) {
            $total = BloodUnit::where('blood_group', $group)->count();
            $available = BloodUnit::where('blood_group', $group)->available()->count();
            BloodInventory::updateOrCreate(
                ['blood_group' => $group],
                [
                    'total_units' => $total,
                    'available_units' => $available,
                    'threshold' => 10
                ]
            );
        }
    }

    public function refreshInventory()
    {
        $this->syncAllInventory();
        return redirect()->route('admin.inventories.index')
            ->with('success', 'Inventory synced from BloodUnit table!');
    }
}

