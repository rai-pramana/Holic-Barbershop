<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Barber;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if already seeded (idempotent — safe for Railway restarts)
        if (User::where('email', 'admin@holic.com')->exists()) {
            return;
        }

        // ── Admin ───────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Admin HOLIC',
            'email'    => 'admin@holic.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '081200000001',
        ]);

        // ── Branches ────────────────────────────────────────────────────────
        $branch1 = Branch::create([
            'name'         => 'HOLIC Barbershop - Pusat',
            'address'      => 'Jl. Sudirman No. 1, Jakarta Pusat',
            'phone'        => '021-1234567',
            'city'         => 'Jakarta',
            'description'  => 'Cabang utama HOLIC Barbershop dengan 5 barber profesional.',
            'open_time'    => '09:00',
            'close_time'   => '21:00',
            'is_active'    => true,
            'queue_prefix' => '0',
        ]);

        $branch2 = Branch::create([
            'name'         => 'HOLIC Barbershop - Selatan',
            'address'      => 'Jl. TB Simatupang No. 25, Jakarta Selatan',
            'phone'        => '021-7654321',
            'city'         => 'Jakarta',
            'description'  => 'Cabang HOLIC di Jakarta Selatan.',
            'open_time'    => '09:00',
            'close_time'   => '21:00',
            'is_active'    => true,
            'queue_prefix' => '1',
        ]);

        // ── Barbers (no user account required) ──────────────────────────────
        // Branch 1
        Barber::create([
            'name'         => 'Budi Santoso',
            'phone'        => '081200000002',
            'branch_id'    => $branch1->id,
            'specialty'    => 'Fade & Modern Cut',
            'bio'          => 'Spesialis fade dan modern haircut dengan 5 tahun pengalaman.',
            'is_available' => true,
        ]);
        Barber::create([
            'name'         => 'Rizky Pratama',
            'phone'        => '081200000003',
            'branch_id'    => $branch1->id,
            'specialty'    => 'Classic & Pompadour',
            'bio'          => 'Master classic cut dan pompadour style.',
            'is_available' => true,
        ]);

        // Branch 2
        Barber::create([
            'name'         => 'Deni Kurnia',
            'phone'        => '081200000004',
            'branch_id'    => $branch2->id,
            'specialty'    => 'Skin Fade & Design',
            'bio'          => 'Ahli skin fade dan desain rambut kreatif.',
            'is_available' => true,
        ]);

        // ── Services Branch 1 ───────────────────────────────────────────────
        $services1 = [
            ['name' => 'Potong Rambut',      'desc' => 'Potong rambut standar dengan konsultasi gaya.',                'dur' => 30, 'price' => 35000],
            ['name' => 'Cukur Jenggot',      'desc' => 'Cukur dan rapikan jenggot dengan teknik barbershop klasik.',   'dur' => 20, 'price' => 25000],
            ['name' => 'Potong + Cukur',     'desc' => 'Paket lengkap potong rambut dan cukur jenggot.',               'dur' => 45, 'price' => 55000],
            ['name' => 'Creambath & Potong', 'desc' => 'Creambath relaxing ditambah potong rambut.',                   'dur' => 60, 'price' => 80000],
            ['name' => 'Warna Rambut',       'desc' => 'Pewarnaan rambut dengan cat berkualitas.',                     'dur' => 90, 'price' => 150000],
        ];
        foreach ($services1 as $s) {
            Service::create(['branch_id' => $branch1->id, 'name' => $s['name'], 'description' => $s['desc'], 'duration_minutes' => $s['dur'], 'price' => $s['price'], 'is_active' => true]);
        }

        // ── Services Branch 2 ───────────────────────────────────────────────
        $services2 = [
            ['name' => 'Potong Rambut',  'desc' => 'Potong rambut standar.',    'dur' => 30, 'price' => 35000],
            ['name' => 'Cukur Jenggot',  'desc' => 'Cukur jenggot presisi.',    'dur' => 20, 'price' => 25000],
            ['name' => 'Potong + Cukur', 'desc' => 'Paket lengkap.',            'dur' => 45, 'price' => 55000],
        ];
        foreach ($services2 as $s) {
            Service::create(['branch_id' => $branch2->id, 'name' => $s['name'], 'description' => $s['desc'], 'duration_minutes' => $s['dur'], 'price' => $s['price'], 'is_active' => true]);
        }

        // ── Demo Customer ───────────────────────────────────────────────────
        User::create([
            'name'     => 'Customer Demo',
            'email'    => 'customer@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'phone'    => '081200000099',
        ]);
    }
}
