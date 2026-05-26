<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Society;
use App\Models\Building;
use App\Models\Flate;
use App\Models\Resident;
use App\Models\Amenity;
use App\Models\ParkingSlot;
use App\Models\Notice;
use App\Models\Complaint;
use App\Models\Maintenance;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Premium Society
        $society = Society::create([
            'name' => 'Emerald Heights Co-operative Housing Society',
            'code' => 'EMHL',
            'address' => '102 Luxury Green Blvd, Sector 45, Metro City',
            'email' => 'contact@emeraldheights.com',
            'phone' => '+1 (555) 019-2834',
            'logo' => null,
            'subscription_plan' => 'Elite',
            'status' => 'active',
            'expires_at' => now()->addYear(),
        ]);

        // 2. Create Super Admin (Global scope, no society_id needed)
        User::create([
            'name' => 'Super Admin Portal',
            'email' => 'super@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '+1 (555) 000-0000',
            'status' => 'active',
            'society_id' => null,
        ]);

        // 3. Create Society Admin
        $societyAdmin = User::create([
            'name' => 'Emerald Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1 (555) 111-1111',
            'status' => 'active',
            'society_id' => $society->id,
        ]);

        // 4. Create Guard User
        User::create([
            'name' => 'Officer Ram Singh',
            'email' => 'guard@example.com',
            'password' => Hash::make('password'),
            'role' => 'guard',
            'phone' => '+1 (555) 222-2222',
            'status' => 'active',
            'society_id' => $society->id,
        ]);

        // 5. Create Resident User Account
        $residentUser = User::create([
            'name' => 'John Doe',
            'email' => 'resident@example.com',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'phone' => '+1 (555) 333-3333',
            'status' => 'active',
            'society_id' => $society->id,
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
        ]);

        // 6. Create Building Wing
        $building = Building::create([
            'society_id' => $society->id,
            'building_name' => 'Wing A - Emerald',
        ]);

        // 7. Create Flat
        $flat = Flate::create([
            'society_id' => $society->id,
            'building_id' => $building->id,
            'flate_number' => 'A-101',
            'floor' => '1st Floor',
            'owner_name' => 'John Doe',
            'owner_phone' => '+1 (555) 333-3333',
            'owner_email' => 'resident@example.com',
            'status' => 'occupied',
        ]);

        // 8. Create Resident Profile
        $resident = Resident::create([
            'society_id' => $society->id,
            'flate_id' => $flat->id,
            'user_id' => $residentUser->id,
            'name' => 'John Doe',
            'email' => 'resident@example.com',
            'phone' => '+1 (555) 333-3333',
            'family_members' => '3 Members',
            'image' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
        ]);

        // 9. Seed Amenities
        $amenityClub = Amenity::create([
            'society_id' => $society->id,
            'name' => 'Grand Clubhouse',
            'description' => 'Fully air-conditioned indoor hall with pool table, table tennis, and home theater system.',
            'capacity' => 60,
            'status' => 'Active',
        ]);
        $amenityPool = Amenity::create([
            'society_id' => $society->id,
            'name' => 'Infinity Swimming Pool',
            'description' => 'Outdoor heated swimming pool with jacuzzi and children play section.',
            'capacity' => 25,
            'status' => 'Active',
        ]);
        Amenity::create([
            'society_id' => $society->id,
            'name' => 'Premium Gymnasium',
            'description' => 'State-of-the-art strength and cardio training equipment with on-demand personal trainers.',
            'capacity' => 15,
            'status' => 'Active',
        ]);

        // 10. Seed Parking Slots
        ParkingSlot::create([
            'society_id' => $society->id,
            'slot_number' => 'P-101 (Basement)',
            'vehicle_type' => '4-wheeler',
            'status' => 'Allocated',
            'flate_id' => $flat->id,
        ]);
        ParkingSlot::create([
            'society_id' => $society->id,
            'slot_number' => 'P-102 (Basement)',
            'vehicle_type' => '4-wheeler',
            'status' => 'Available',
            'flate_id' => null,
        ]);
        ParkingSlot::create([
            'society_id' => $society->id,
            'slot_number' => 'P-103 (Ground Floor)',
            'vehicle_type' => '2-wheeler',
            'status' => 'Available',
            'flate_id' => null,
        ]);

        // 11. Seed Notices
        Notice::create([
            'society_id' => $society->id,
            'title' => 'Annual Society General Body Meeting (AGM)',
            'description' => 'The Annual General Body Meeting of the Emerald Heights Co-operative Housing Society will be held on coming Sunday at 10:00 AM at the Grand Clubhouse. All owners are requested to attend. Agenda items include audit reports approval, new security guard deployment, and amenity booking guidelines discussion.',
            'category' => 'Announcement',
            'notice_date' => now(),
            'scheduled_at' => now(),
        ]);
        Notice::create([
            'society_id' => $society->id,
            'title' => 'Monsoon Waterproofing Work in Basement',
            'description' => 'Waterproofing repair and sealant injection will be done in the lower basement parking sector B on Saturday between 9:00 AM and 6:00 PM. Please shift vehicles parked in slots B-01 to B-12 to temporary ground level parking. We apologize for the inconvenience.',
            'category' => 'Emergency',
            'notice_date' => now()->subDays(2),
            'scheduled_at' => now()->subDays(2),
        ]);
        Notice::create([
            'society_id' => $society->id,
            'title' => 'Summer Swimming Championship 2026',
            'description' => 'Register your children for the upcoming Summer Swimming Competition to be held on June 5th. Categories range from Age 8 to 16. Awards, certificates, and snack packs will be distributed. Register today at the security office desk.',
            'category' => 'Event',
            'notice_date' => now()->subDays(5),
            'scheduled_at' => now()->subDays(5),
        ]);

        // 12. Seed Complaints
        Complaint::create([
            'society_id' => $society->id,
            'resident_id' => $resident->id,
            'title' => 'Water pressure low in bathroom',
            'category' => 'Plumbing',
            'priority' => 'Medium',
            'description' => 'The water pressure in both the guest bathroom and master bedroom toilet has dropped significantly since this morning. It takes a very long time to fill the flushing cistern.',
            'status' => 'pending',
            'admin_reply' => null,
        ]);
        Complaint::create([
            'society_id' => $society->id,
            'resident_id' => $resident->id,
            'title' => 'Basement tube light flickers',
            'category' => 'Electrical',
            'priority' => 'Low',
            'description' => 'The tube light right above parking slot P-101 has been flickering constantly. It causes visibility issues when parking late at night.',
            'status' => 'resolved',
            'admin_reply' => 'Our local electrician has replaced the faulty LED choke. Please verify and confirm.',
        ]);

        // 13. Seed Maintenance Bills
        // Unpaid current month bill
        $m1 = Maintenance::create([
            'society_id' => $society->id,
            'resident_id' => $resident->id,
            'month' => 'May 2026',
            'amount' => 3200.00,
            'late_fee' => 0.00,
            'due_date' => now()->addDays(10),
            'payment_status' => 'unpaid',
            'invoice_pdf' => null,
        ]);
        // Paid past month bill
        $m2 = Maintenance::create([
            'society_id' => $society->id,
            'resident_id' => $resident->id,
            'month' => 'April 2026',
            'amount' => 3200.00,
            'late_fee' => 0.00,
            'due_date' => now()->subDays(15),
            'payment_status' => 'paid',
            'invoice_pdf' => 'invoices/receipt_april_2026.pdf',
        ]);

        // 14. Seed Payments
        Payment::create([
            'maintenance_id' => $m2->id,
            'resident_id' => $resident->id,
            'amount' => 3200.00,
            'payment_method' => 'Razorpay',
            'transaction_id' => 'pay_EMH_4920491024',
            'status' => 'success',
            'receipt_number' => 'REC-EMH-2026-0089',
        ]);
    }
}
