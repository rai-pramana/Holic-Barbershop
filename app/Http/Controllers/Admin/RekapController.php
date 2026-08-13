<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Branch;
use App\Models\Queue;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekapController extends Controller
{
    public function index(Request $request): View
    {
        // ── Date range resolution ──────────────────────────────────────────
        $preset   = $request->get('preset', 'today');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        // If preset is a known shortcut, always use it (ignore date_from/date_to)
        if (in_array($preset, ['today', 'yesterday', 'week', 'month'])) {
            [$from, $to] = match ($preset) {
                'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
                'week'      => [now()->startOfWeek(), now()->endOfWeek()],
                'month'     => [now()->startOfMonth(), now()->endOfMonth()],
                default     => [now()->startOfDay(), now()->endOfDay()], // today
            };
        } elseif ($dateFrom && $dateTo) {
            $from = Carbon::parse($dateFrom)->startOfDay();
            $to   = Carbon::parse($dateTo)->endOfDay();
            $preset = 'custom';
        } elseif ($dateFrom) {
            $from = Carbon::parse($dateFrom)->startOfDay();
            $to   = Carbon::parse($dateFrom)->endOfDay();
            $preset = 'custom';
        } else {
            $from = now()->startOfDay();
            $to   = now()->endOfDay();
            $preset = 'today';
        }

        $branchId = $request->get('branch_id');
        $branches = Branch::where('is_active', true)->get();

        // ── Base query builder ─────────────────────────────────────────────
        $base = Queue::whereBetween('created_at', [$from, $to]);
        if ($branchId) {
            $base->where('branch_id', $branchId);
        }

        // ── Top KPIs ──────────────────────────────────────────────────────
        $total      = (clone $base)->count();
        $completed  = (clone $base)->where('status', 'completed')->count();
        $skipped    = (clone $base)->where('status', 'skipped')->count();
        $expired    = (clone $base)->where('status', 'expired')->count();
        $active     = (clone $base)->whereIn('status', ['active', 'called', 'pending'])->count();

        $checkedIn   = (clone $base)->whereNotNull('checked_in_at')->count();
        $attendRate  = $total > 0 ? round($checkedIn / $total * 100) : 0;
        $completeRate = $checkedIn > 0 ? round($completed / $checkedIn * 100) : 0;

        // Average service duration (called_at → completed_at)
        $avgMinutes = null;
        $durationRows = (clone $base)
            ->where('status', 'completed')
            ->whereNotNull('called_at')
            ->whereNotNull('completed_at')
            ->get(['called_at', 'completed_at']);

        if ($durationRows->isNotEmpty()) {
            $avgSeconds = $durationRows->avg(fn($q) => abs(Carbon::parse($q->completed_at)->diffInSeconds(Carbon::parse($q->called_at))));
            $avgMinutes = round($avgSeconds / 60);
        }

        // ── Per-barber breakdown ───────────────────────────────────────────
        $barberStats = Barber::query()
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->with(['branch'])
            ->get()
            ->map(function (Barber $barber) use ($from, $to) {
                $q = Queue::where('barber_id', $barber->id)
                    ->whereBetween('created_at', [$from, $to]);
                $total     = (clone $q)->count();
                $done      = (clone $q)->where('status', 'completed')->count();
                $skip      = (clone $q)->where('status', 'skipped')->count();
                $exp       = (clone $q)->where('status', 'expired')->count();
                $rate      = $total > 0 ? round($done / $total * 100) : 0;
                return [
                    'name'     => $barber->name,
                    'branch'   => $barber->branch->name ?? '-',
                    'total'    => $total,
                    'completed'=> $done,
                    'skipped'  => $skip,
                    'expired'  => $exp,
                    'rate'     => $rate,
                ];
            })
            ->filter(fn($b) => $b['total'] > 0)
            ->sortByDesc('total')
            ->values();

        // ── Per-service breakdown ──────────────────────────────────────────
        $serviceStats = Service::query()
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->get()
            ->map(function (Service $service) use ($from, $to) {
                $q = Queue::where('service_id', $service->id)
                    ->whereBetween('created_at', [$from, $to]);
                $total  = (clone $q)->count();
                $done   = (clone $q)->where('status', 'completed')->count();
                $revenue = $done * $service->price;
                return [
                    'name'    => $service->name,
                    'total'   => $total,
                    'completed'=> $done,
                    'revenue' => $revenue,
                    'price'   => $service->price,
                ];
            })
            ->filter(fn($s) => $s['total'] > 0)
            ->sortByDesc('total')
            ->values();

        // ── Hourly distribution (0–23) ────────────────────────────────────
        $hourlyData = array_fill(0, 24, 0);
        (clone $base)->get(['created_at'])->each(function ($q) use (&$hourlyData) {
            $hour = Carbon::parse($q->created_at)->hour;
            $hourlyData[$hour]++;
        });
        $peakHour    = array_search(max($hourlyData), $hourlyData);
        $hourlyMax   = max($hourlyData) ?: 1; // avoid division by zero

        // ── Estimated revenue ─────────────────────────────────────────────
        $totalRevenue = $serviceStats->sum('revenue');

        return view('admin.rekap.index', compact(
            'from', 'to', 'preset', 'branches', 'branchId',
            'total', 'completed', 'skipped', 'expired', 'active',
            'attendRate', 'completeRate', 'avgMinutes',
            'barberStats', 'serviceStats',
            'hourlyData', 'hourlyMax', 'peakHour',
            'totalRevenue'
        ));
    }
}
