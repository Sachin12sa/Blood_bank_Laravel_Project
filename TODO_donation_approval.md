# Donation Approval Workflow

## Plan Steps:

### 1. [✅ COMPLETE] Migration: donations.status enum added & migrated
`php artisan make:migration add_status_to_donations_table --table=donations`

### 2. [✅ COMPLETE] Update Donor model + donation views

### 3. [✅ COMPLETE] DonorController@donate → pending donation + admin notification

### 4. [PENDING] Create AdminDonationController + views for approval

### 5. [PENDING] Create DonationApproved notification + certificate

**Status: Ready to start migration**

