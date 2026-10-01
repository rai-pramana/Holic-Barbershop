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
 * 100+ customer + sebar ulang antrean: tiap barber dilayani
 * 3-5 customer unik yang bervariasi. HANYA lokal/demo.
 */
class DummyCustomersSeeder extends Seeder
{
    public function run(): void
    {
        $first = ['Made', 'Wayan', 'Nyoman', 'Ketut', 'Putu', 'Kadek', 'Komang', 'Gede', 'Agus', 'Budi', 'Andi', 'Dewi', 'Siti', 'Rina', 'Maya', 'Putri', 'Ayu', 'Dedi', 'Hendra', 'Fajar', 'Yoga', 'Bayu', 'Eka', 'Dimas', 'Rudi', 'Rudi', 'Ilham', 'Rian', 'Bagus', 'Tude', 'Raka', 'Sandi', 'Eko', 'Wira', 'Yoga', 'Dana', 'Suta', 'Jaya', 'Adit', 'Vina', 'Lestari', 'Nadia', 'Tiara', 'Bagas', 'Fikri', 'Galih', 'Haris', 'Irvan', 'Jefri', 'Krisna', 'Lukman', 'Mahendra', 'Nando', 'Oka', 'Pandu', 'Qori', 'Raka', 'Surya', 'Teguh', 'Utama', 'Wibawa', 'Yudi', 'Arta', 'Brata', 'Candra', 'Darma', 'Erlangga', 'Febri', 'Guna', 'Harta', 'Irfan', 'Jaya', 'Kerta', 'Laksana', 'Mega', 'Nata', 'Oscar', 'Prama', 'Ridho', 'Satria', 'Tirta', 'Wacika', 'Yadnya', 'Darmawan', 'Gunawan', 'Halim', 'Irawan', 'Junaedi', 'Kusuma', 'Laksmana', 'Maulana', 'Nugraha', 'Pratama', 'Ramadhan', 'Saputra', 'Taufik', 'Wijaya'];
        $last = ['Susilo', 'Wirawan', 'Arya', 'Prasetya', 'Santoso', 'Pratama', 'Nugroho', 'Saputra', 'Wijaya', 'Kurniawan', 'Setiawan', 'Hidayat', 'Gunawan', 'Lestari', 'Marlina', 'Anggraini', 'Puspita', 'Rahmawati', 'Sari', 'Wulandari', 'Maharani', 'Septiani', 'Utami', 'Pratiwi', 'Amalia', 'Safitri', 'Hapsari', 'Kirana', 'Sekar', 'Wangi'];

        $made = 0;
        $existingDemo = User::where('email', 'like', '%@demo.com')->count();
        if ($existingDemo >= 100) {
            $this->command->info('Customers cukup (' . $existingDemo . '), lewati pembuatan.');
        } else {
        $i = 0;
        while ($made < 100) {
            $nm = $first[$i % count($first)] . ' ' . $last[($i * 7) % count($last)];
            $em = strtolower(str_replace(' ', '.', $nm)) . '.' . $i . '@demo.com';
            if (! User::where('email', $em)->exists()) {
                User::create(['name' => $nm, 'email' => $em, 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '08' . substr((string) (1200000000 + $i * 104729), -10), 'email_verified_at' => now()]);
                $made++;
            }
            $i++;
            if ($i > 500) break;
        }
        }
        $this->command->info('New customers: ' . $made);

        // ── Sebar ulang antrean ──────────────────────────────────────────
        Queue::query()->delete();
        $branches = Branch::orderBy('id')->get();
        $barbers = Barber::orderBy('id')->get();
        $customers = User::where('role', 'customer')->where('email', '!=', 'walkin@system.local')->orderBy('id')->get();
        $guest = User::where('email', 'walkin@system.local')->first();
        $services = Service::all()->groupBy('branch_id');

        $guestNames = ['Budi Santoso', 'Andi Pratama', 'Siti Aminah', 'Hendra Gunawan', 'Maya Sari', 'Fajar Nugroho', 'Rina Marlina', 'Dedi Kurniawan'];
        $notesPool = [null, null, null, null, 'Potong pendek samping', 'Jangan terlalu pendek', 'Creambath sekalian', 'Anak kecil, mohon sabar', 'Acara kondangan Sabtu', 'Langganan tiap bulan'];
        $svcW = [0, 0, 0, 1, 2, 2, 2, 3, 4, 5];
        $stW = ['completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'skipped', 'expired', 'completed'];

        // Peta pasangan eksplisit: 20 barber x 4 user = 80 pasangan.
        // Barber b layani pool[(b*2+k) % 40], k=0..3 (tumpang tindih 2).
        // Tiap pasangan 5 antrean tersebar tanggal => tiap pool user
        // muncul di ~2 barber x 6 = 12 riwayat; unik/barber = 4
        // (+guest sesekali, di user yang sama).
        $start = \Carbon\Carbon::create(2026, 9, 17);
        $seq = 0;
        $counters = [];
        $pool = array_values($customers->reject(fn ($u) => $u->email === 'walkin@system.local')->take(40)->all());
        $poolCount = count($pool);
        $nyankoKey = array_search(5, array_map(fn ($u) => $u->id, $pool));
        $pairs = [];
        foreach ($barbers as $bi => $barb) {
            for ($k = 0; $k < 4; $k++) $pairs[] = [$barb->id, ($bi * 2 + $k) % $poolCount];
        }
        $seed = 7;
        $rnd = function () use (&$seed) { $seed = ($seed * 1103515245 + 12345) & 0x7fffffff; return $seed / 0x7fffffff; };
        // 5 antrean per pasangan, tanggal acak 17 Sep - 1 Okt
        $jobs = [];
        foreach ($pairs as [$barbId, $pIdx]) {
            for ($r = 0; $r < 7; $r++) $jobs[] = [$barbId, $pIdx];
        }
        for ($k = count($jobs) - 1; $k > 0; $k--) { $j = (int) floor($rnd() * ($k + 1)); [$jobs[$k], $jobs[$j]] = [$jobs[$j], $jobs[$k]]; }

        foreach ($jobs as [$barbId, $pIdx]) {
            $seq++;
            $barb = $barbers->firstWhere('id', $barbId);
            $br = $branches->firstWhere('id', $barb->branch_id);
            $dayOff = (int) floor(pow($rnd(), 0.6) * 15);
            $date = $start->copy()->addDays($dayOff)->toDateString();
            $hh = 9 + (int) floor($rnd() * 11);
            $mm = (int) floor($rnd() * 60);
            $t = sprintf('%s %02d:%02d:00', $date, $hh, $mm);
            $svc = $services[$br->id][$svcW[$seq % 10]];
            $st = $stW[$seq % 10];
            // 12% guest (nama jalanan), sisanya user pasangan; tiap
            // pasangan ke-20 disisipi Nyanko agar riwayatnya kaya
            $isGuest = $rnd() < 0.08;
            if ($isGuest) {
                $custId = $guest->id;
                $gname = $guestNames[$seq % 8];
                $gphone = '08' . substr((string) (1200000000 + $seq * 7919), -10);
            } else {
                if (false && $seq % 20 === 0 && $nyankoKey !== false) {
                    $custIdx = $nyankoKey;
                } else {
                    $custIdx = $pIdx;
                }
                $custId = $pool[$custIdx]->id;
                $gname = null; $gphone = null;
            }
            $key = $br->id . '|' . $date;
            $counters[$key] = ($counters[$key] ?? 0) + 1;
            Queue::create([
                'queue_number' => 'Q' . str_pad((string) $counters[$key], 4, '0', STR_PAD_LEFT),
                'customer_id' => $custId, 'barber_id' => $barbId, 'service_id' => $svc->id, 'branch_id' => $br->id,
                'status' => $st, 'notes' => $notesPool[$seq % 10],
                'guest_name' => $gname, 'guest_phone' => $gphone,
                'validation_token' => Str::random(32),
                'checked_in_at' => in_array($st, ['pending', 'expired']) ? null : $t,
                'called_at' => in_array($st, ['called', 'completed', 'skipped']) ? $t : null,
                'completed_at' => $st === 'completed' ? $t : null,
                'expired_at' => $st === 'expired' ? $t : null,
                'created_at' => $t, 'updated_at' => $t,
            ]);
        }
        // Perbaiki durasi completed: called +8 mnt, selesai +durasi layanan
        foreach (Queue::where('status', 'completed')->with('service')->get() as $q) {
            $q->update([
                'called_at' => \Carbon\Carbon::parse($q->created_at)->addMinutes(8),
                'completed_at' => \Carbon\Carbon::parse($q->created_at)->addMinutes(8 + (int) $q->service->duration_minutes),
            ]);
        }
        $this->command->info('Queues: ' . Queue::count());
    }
}
