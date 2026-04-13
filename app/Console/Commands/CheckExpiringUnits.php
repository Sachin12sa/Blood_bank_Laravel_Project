<?php

namespace App\Console\Commands;

use App\Models\BloodUnit;
use App\Models\ExpiryAlert;
use App\Models\User;
use App\Notifications\ExpiryWarning;
use Illuminate\Console\Command;

class CheckExpiringUnits extends Command
{
    protected $signature = 'bloodbank:check-expiring';
    protected $description = 'Check for blood units expiring within 3 days and mark expired units';

    public function handle()
    {
        // Mark expired units
        $expired = BloodUnit::where('status', 'available')
            ->where('expires_at', '<', now()->toDateString())
            ->get();

        foreach ($expired as $unit) {
            $unit->update(['status' => 'expired']);
            $this->warn("EXPIRED: Unit #{$unit->id} ({$unit->blood_group}) expired on {$unit->expires_at->format('M d, Y')}");
        }

        // Find units expiring within 3 days
        $expiringSoon = BloodUnit::expiringSoon(3)->get();

        if ($expiringSoon->isEmpty() && $expired->isEmpty()) {
            $this->info('No expiring or expired units found.');
            return 0;
        }

        // Create expiry alerts and notify admins
        foreach ($expiringSoon as $unit) {
            $existingAlert = ExpiryAlert::where('blood_unit_id', $unit->id)
                ->where('notified', true)
                ->exists();

            if (!$existingAlert) {
                ExpiryAlert::create([
                    'blood_unit_id' => $unit->id,
                    'notified'      => true,
                    'alerted_at'    => now(),
                ]);
            }
        }

        // Notify admins
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ExpiryWarning($expiringSoon, $expired));
        }

        $this->info("Expiry check complete: {$expired->count()} expired, {$expiringSoon->count()} expiring soon.");
        return 0;
    }
}
