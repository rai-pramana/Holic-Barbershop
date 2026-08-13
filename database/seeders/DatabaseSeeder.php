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
            'phone'    => '089606299992',
        ]);

        // ── Branches ────────────────────────────────────────────────────────
        $branch1 = Branch::create([
            'name'         => 'Cabang Gianyar',
            'address'      => 'Jalan Kesatrian No. 15, Gianyar',
            'phone'        => '089606299992',
            'city'         => 'Gianyar',
            'description'  => 'Cabang utama HOLIC Barbershop di Gianyar. Lokasi strategis di pusat kota dengan suasana nyaman dan peralatan modern.',
            'open_time'    => '09:00',
            'close_time'   => '21:00',
            'is_active'    => true,
            'queue_prefix' => '0',
        ]);

        // ── Barbers (dummy names, with descriptions) ────────────────────────
        Barber::create([
            'name'         => 'Budi Santoso',
            'phone'        => '081200000002',
            'branch_id'    => $branch1->id,
            'specialty'    => 'Fade & Modern Cut',
            'bio'          => 'Spesialis fade dan modern haircut dengan 5 tahun pengalaman. Ahli dalam teknik skin fade, taper fade, dan gaya rambut kekinian.',
            'is_available' => true,
        ]);
        Barber::create([
            'name'         => 'Rizky Pratama',
            'phone'        => '081200000003',
            'branch_id'    => $branch1->id,
            'specialty'    => 'Classic & Pompadour',
            'bio'          => 'Master classic cut dan pompadour style. Berpengalaman menangani berbagai tipe rambut dengan hasil rapi dan presisi.',
            'is_available' => true,
        ]);
        Barber::create([
            'name'         => 'Deni Kurnia',
            'phone'        => '081200000004',
            'branch_id'    => $branch1->id,
            'specialty'    => 'Skin Fade & Design',
            'bio'          => 'Ahli skin fade dan desain rambut kreatif. Selalu update dengan tren gaya rambut terbaru.',
            'is_available' => true,
        ]);

        // ── Services ────────────────────────────────────────────────────────
        $services = [
            [
                'name'  => 'Cukur Rambut',
                'desc'  => 'Layanan cukur rambut standar dengan konsultasi gaya. Termasuk finishing dan styling ringan.',
                'dur'   => 30,
                'price' => 35000,
            ],
            [
                'name'  => 'Cukur + Keramas',
                'desc'  => 'Paket cukur rambut lengkap dengan keramas menggunakan shampoo berkualitas. Rambut bersih dan segar setelah potong.',
                'dur'   => 40,
                'price' => 40000,
            ],
            [
                'name'  => 'Cat Rambut Hitam',
                'desc'  => 'Pewarnaan rambut hitam untuk menutupi uban atau menyegarkan warna rambut. Menggunakan cat rambut berkualitas yang aman.',
                'dur'   => 40,
                'price' => 55000,
            ],
        ];
        foreach ($services as $s) {
            Service::create([
                'branch_id'        => $branch1->id,
                'name'             => $s['name'],
                'description'      => $s['desc'],
                'duration_minutes' => $s['dur'],
                'price'            => $s['price'],
                'is_active'        => true,
            ]);
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
