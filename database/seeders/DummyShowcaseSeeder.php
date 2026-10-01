<?php

namespace Database\Seeders;

use App\Models\Barber;
use App\Models\Branch;
use App\Models\Queue;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Dummy showcase: 5 cabang Bali, 4 barber/cabang, 120+ antrean
 * tersebar 18 Sep - 1 Okt 2026. HANYA lokal/demo.
 */
class DummyShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        // Idempoten: pakai master yang sudah ada bila tersedia.
        $branches = Branch::orderBy('id')->take(5)->get();
        if ($branches->count() < 5) {
        $branchesData = [
            ['HOLIC Barbershop - Gianyar Kota', 'Jl. Kesatrian No. 15, Gianyar', '0361-943210', 'Gianyar', 'Cabang utama di jantung Kota Gianyar. Ruang tunggu nyaman berpendingin dengan peralatan modern.'],
            ['HOLIC Barbershop - Ubud', 'Jl. Monkey Forest No. 88, Ubud, Gianyar', '0361-975432', 'Gianyar', 'Cabang bernuansa tropis di kawasan wisata Ubud, favorit wisatawan domestik dan mancanegara.'],
            ['HOLIC Barbershop - Denpasar Timur', 'Jl. WR Supratman No. 214, Denpasar', '0361-445566', 'Denpasar', 'Lokasi strategis dengan area parkir luas, cocok untuk pelanggan korporat dan keluarga.'],
            ['HOLIC Barbershop - Renon', 'Jl. Cok Agung Tresna No. 7, Renon, Denpasar', '0361-223344', 'Denpasar', 'Cabang modern di kawasan perkantoran Renon. Layanan cepat dan presisi untuk profesional sibuk.'],
            ['HOLIC Barbershop - Sanur', 'Jl. Danau Tamblingan No. 45, Sanur, Denpasar', '0361-287654', 'Denpasar', 'Cabang tepi pantai Sanur dengan suasana santai dan angin sepoi-sepoi.'],
        ];
        $branches = $branches->all();
        } // else: pakai 5 cabang existing

        $barbers = Barber::orderBy('id')->take(20)->get()->all();
        if (count($barbers) < 20) {
        $barberData = [
            ['Joko Susilo', 'Fade & Modern Cut'], ['Made Wirawan', 'Classic & Pompadour'], ['Komang Arya', 'Skin Fade & Design'], ['Putu Eka', 'Kids & Family Cut'],
            ['Agus Setiawan', 'Premium Styling'], ['Wayan Sudarma', 'Traditional & Modern'], ['Kadek Dwi', 'Beard & Detail'], ['Nyoman Tri', 'Color & Treatment'],
            ['Gede Purnama', 'Classic Gentleman'], ['Ilham Ramadan', 'Urban Street Style'], ['Yoga Pratama', 'Fade & Taper'], ['Rian Hidayat', 'Curly & Texture'],
            ['Bayu Aditya', 'Executive Cut'], ['Dewa Gede', 'Artistic Design'], ['Fajar Ramadhan', 'Sporty Look'], ['Eko Saputra', 'Vintage & Retro'],
            ['Tude Marjaya', 'Luxury Grooming'], ['Raka Pradipta', 'Korean Style'], ['Sandi Kurnia', 'Buzz & Crew Cut'], ['Bagus Nugraha', 'All-round Stylist'],
        ];
        $barbers = $barbers ?? [];
        foreach ($barberData as $i => [$name, $spec]) {
            $barbers[] = Barber::create(['name' => $name, 'phone' => '0812' . substr((string) (10000000 + $i * 1379137), -8), 'branch_id' => $branches[$i % 5]->id, 'specialty' => $spec, 'bio' => $spec . ' profesional di ' . $branches[$i % 5]->name . '.', 'is_available' => true]);
        }
        } // else: pakai 20 barber existing

        $services = [];
        $branchIds = $branches instanceof \Illuminate\Support\Collection ? $branches->pluck('id')->all() : array_map(fn ($b) => $b->id, $branches);
        $existingSvc = Service::whereIn('branch_id', $branchIds)->get()->groupBy('branch_id');
        $needSvc = collect($branches)->contains(fn ($b) => ($existingSvc[$b->id] ?? collect())->count() < 6);
        if ($needSvc) {
        $svcTpl = [
            ['Potong Rambut', 30, 35000], ['Potong + Keramas', 40, 40000], ['Potong + Cukur', 45, 55000],
            ['Cukur Jenggot', 20, 25000], ['Creambath & Potong', 60, 80000], ['Warna Rambut', 90, 150000],
        ];
        foreach ($branches as $br) {
            foreach ($svcTpl as [$nm, $dur, $pr]) {
                $services[$br->id][] = Service::firstOrCreate(['branch_id' => $br->id, 'name' => $nm], ['description' => $nm . ' profesional.', 'duration_minutes' => $dur, 'price' => $pr, 'is_active' => true]);
            }
        }
        } else {
            foreach ($branches as $br) { $services[$br->id] = $existingSvc[$br->id]->all(); }
        }

        $custData = [
            ['Customer Nyanko', 'customer@demo.com', '081234567001'], ['Rai Pramana', 'rai.pramana46@gmail.com', '088236053449'],
            ['Agus Wijaya', 'agus.wijaya@demo.com', '081234567002'], ['Dewi Lestari', 'dewi.lestari@demo.com', '081234567003'],
            ['Putu Surya', 'putu.surya@demo.com', '081234567004'], ['Kadek Santosa', 'kadek.santosa@demo.com', '081234567005'],
        ];
        $customers = [];
        foreach ($custData as [$nm, $em, $ph]) {
            $customers[] = User::firstOrCreate(['email' => $em], ['name' => $nm, 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => $ph, 'email_verified_at' => now()]);
        }
        $guest = User::firstOrCreate(['email' => 'walkin@system.local'], ['name' => 'Walk-in Guest', 'password' => Hash::make(Str::random(32)), 'role' => 'customer']);

        $guestNames = ['Budi Santoso', 'Andi Pratama', 'Siti Aminah', 'Hendra Gunawan', 'Maya Sari', 'Fajar Nugroho', 'Rina Marlina', 'Dedi Kurniawan', 'Yoga Saputra', 'Bayu Firmansyah', 'Eka Prasetya', 'Dimas Angga'];
        $notesPool = [null, null, null, 'Potong pendek samping, atas sisakan', 'Jangan terlalu pendek', 'Creambath sekalian', 'Anak kecil, mohon sabar', '2 orang (bapak dan anak)', 'Acara kondangan Sabtu'];
        $svcW = [0, 0, 0, 1, 2, 2, 2, 3, 4, 5];
        $stW = ['completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'skipped', 'expired', 'completed'];

        $dayCounts = [6, 7, 5, 8, 6, 7, 5, 9, 6, 8, 7, 9, 8, 10, 19];
        $start = \Carbon\Carbon::create(2026, 9, 17);
        $seq = 0;
        $perUser = array_fill(0, 6, 0);
        $counters = [];
        foreach ($dayCounts as $dd => $count) {
            $date = $start->copy()->addDays($dd)->toDateString();
            for ($i = 0; $i < $count; $i++) {
                $seq++;
                $br = $branches[$seq % 5];
                $hh = 9 + ($seq * 7 + $dd * 3) % 11;
                $mm = ($seq * 13) % 60;
                $t = "$date " . str_pad((string) $hh, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string) $mm, 2, '0', STR_PAD_LEFT) . ':00';
                $svc = $services[$br->id][$svcW[$seq % 10]];
                $st = $stW[$seq % 10];
                $ui = ($seq * 5 + $dd) % 6;
                $isGuest = (($seq + $dd) % 3 === 0);
                $custId = $isGuest ? $guest->id : $customers[$ui]->id;
                if (! $isGuest) $perUser[$ui]++;
                $brBarbers = array_values(array_filter($barbers, fn ($b) => $b->branch_id === $br->id));
                $barb = ($st === 'completed' || $seq % 3 !== 0) ? $brBarbers[$seq % 4]->id : null;
                $key = $br->id . '|' . $date;
                $counters[$key] = ($counters[$key] ?? 0) + 1;
                Queue::create([
                    'queue_number' => 'Q' . str_pad((string) $counters[$key], 4, '0', STR_PAD_LEFT),
                    'customer_id' => $custId, 'barber_id' => $barb, 'service_id' => $svc->id, 'branch_id' => $br->id,
                    'status' => $st, 'notes' => $notesPool[$seq % 9],
                    'guest_name' => $isGuest ? $guestNames[$seq % 12] : null,
                    'guest_phone' => $isGuest ? '08' . substr((string) (1200000000 + $seq * 7919), -10) : null,
                    'validation_token' => Str::random(32),
                    'checked_in_at' => in_array($st, ['pending', 'expired']) ? null : $t,
                    'called_at' => in_array($st, ['called', 'completed', 'skipped']) ? $t : null,
                    'completed_at' => $st === 'completed' ? $t : null,
                    'expired_at' => $st === 'expired' ? $t : null,
                    'created_at' => $t, 'updated_at' => $t,
                ]);
            }
        }
        $this->command->info('Queues: ' . Queue::count() . ' | per user: ' . implode(',', $perUser));
    }
}
