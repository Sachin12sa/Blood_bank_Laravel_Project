<?php

namespace App\Console\Commands;

use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\User;
use App\Notifications\LowStockAlert;
use App\Notifications\DonorCallout;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    protected $signature = 'bloodbank:check-low-stock';
    protected $description = 'Check blood inventory for groups below threshold and send alerts';

    public function handle()
    {
        $lowGroups = BloodInventory::all()->filter->isBelowThreshold();

        if ($lowGroups->isEmpty()) {
            $this->info('All blood groups are above threshold.');
            return 0;
        }

        // Notify admin users
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new LowStockAlert($lowGroups));
        }

        // Send donor callout for each low group
        foreach ($lowGroups as $inv) {
            $eligibleDonors = Donor::eligible()
                ->byBloodGroup($inv->blood_group)
                ->with('user')
                ->get();

            foreach ($eligibleDonors as $donor) {
                if ($donor->user) {
                    $donor->user->notify(new DonorCallout($inv->blood_group, $inv->available_units, $inv->threshold));
                }
            }

            $this->warn("LOW: {$inv->blood_group} — {$inv->available_units}/{$inv->threshold} units. Notified {$eligibleDonors->count()} donors.");
        }

        $this->info('Low stock check complete. ' . $lowGroups->count() . ' group(s) below threshold.');
        return 0;
    }
}
