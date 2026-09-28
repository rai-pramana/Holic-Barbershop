<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\Sortable;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    use Sortable;

    public function index(Request $request): View
    {
        $query = Branch::withCount(['barbers', 'services']);

        // Filter status: active / inactive (whitelist).
        $statusFilter = $request->query('status_filter', '');
        if (in_array($statusFilter, ['active', 'inactive'], true)) {
            $query->where('is_active', $statusFilter === 'active');
        }

        // Sort: name / created_at langsung; barbers / services via withCount.
        $sort = $request->query('sort', 'created_at');
        $dir = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowed = ['name', 'created_at', 'barbers', 'services'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
            $dir = 'desc';
        }
        $column = $sort === 'barbers' ? 'barbers_count' : ($sort === 'services' ? 'services_count' : ($sort === 'name' ? 'name' : 'branches.created_at'));
        // Tiebreaker id agar paginasi deterministik.
        $query->orderBy($column, $dir)->orderBy('branches.id', $dir);

        $branches = $query->paginate(10)->withQueryString();
        return view('admin.branches.index', compact('branches', 'sort', 'dir', 'statusFilter'));
    }

    public function create(): View
    {
        return view('admin.branches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'phone'        => 'nullable|string|max:20|regex:/^[0-9+()\-\s]+$/',
            'city'         => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'open_time'    => 'required|date_format:H:i',
            'close_time'   => 'required|date_format:H:i',
            'is_active'    => 'boolean',
            'queue_prefix' => 'required|string|max:3|unique:branches,queue_prefix',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Branch::create($validated);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function show(Branch $branch): View
    {
        $branch->loadCount(['barbers', 'services', 'queues']);
        $todayQueues = $branch->todayQueues()->with(['customer', 'barber', 'service'])->get();
        return view('admin.branches.show', compact('branch', 'todayQueues'));
    }

    public function edit(Branch $branch): View
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'phone'        => 'nullable|string|max:20|regex:/^[0-9+()\-\s]+$/',
            'city'         => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'open_time'    => 'required|date_format:H:i',
            'close_time'   => 'required|date_format:H:i',
            'is_active'    => 'boolean',
            'queue_prefix' => 'required|string|max:3|unique:branches,queue_prefix,' . $branch->id,
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $branch->update($validated);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        if ($branch->queues()->exists()) {
            return redirect()->route('admin.branches.index')
                ->with('error', 'Cabang tidak dapat dihapus karena masih memiliki data antrean.');
        }
        $branch->delete();
        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
