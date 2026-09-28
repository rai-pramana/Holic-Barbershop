<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\Sortable;
use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarberController extends Controller
{
    use Sortable;

    public function index(Request $request): View
    {
        $query = Barber::with('branch');

        // Filter cabang (whitelist numerik).
        $branchFilter = $request->query('branch_id', '');
        if ($branchFilter !== '' && ctype_digit((string) $branchFilter)) {
            $query->where('barbers.branch_id', $branchFilter);
        }

        [$query, $sort, $dir] = $this->applySort($query, $request, ['name', 'created_at'], 'created_at', 'desc', 'barbers');
        $barbers = $query->paginate(15)->withQueryString();
        $branches = Branch::orderBy('name')->get();
        return view('admin.barbers.index', compact('barbers', 'sort', 'dir', 'branches', 'branchFilter'));
    }

    public function create(): View
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.barbers.create', compact('branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:20|regex:/^[0-9+()\-\s]+$/',
            'branch_id'    => 'required|exists:branches,id',
            'specialty'    => 'nullable|string|max:255',
            'bio'          => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        Barber::create([
            'name'         => $request->name,
            'phone'        => $request->phone,
            'branch_id'    => $request->branch_id,
            'specialty'    => $request->specialty,
            'bio'          => $request->bio,
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.barbers.index')
            ->with('success', 'Barber berhasil ditambahkan.');
    }

    public function show(Barber $barber): View
    {
        $barber->load('branch');
        $todayQueues = $barber->todayQueues()->with(['customer', 'service'])->latest()->get();
        return view('admin.barbers.show', compact('barber', 'todayQueues'));
    }

    public function edit(Barber $barber): View
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.barbers.edit', compact('barber', 'branches'));
    }

    public function update(Request $request, Barber $barber): RedirectResponse
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:20|regex:/^[0-9+()\-\s]+$/',
            'branch_id'    => 'required|exists:branches,id',
            'specialty'    => 'nullable|string|max:255',
            'bio'          => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $barber->update([
            'name'         => $request->name,
            'phone'        => $request->phone,
            'branch_id'    => $request->branch_id,
            'specialty'    => $request->specialty,
            'bio'          => $request->bio,
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()->route('admin.barbers.index')
            ->with('success', 'Data barber berhasil diperbarui.');
    }

    public function destroy(Barber $barber): RedirectResponse
    {
        if ($barber->queues()->exists()) {
            return redirect()->route('admin.barbers.index')
                ->with('error', 'Barber tidak dapat dihapus karena masih memiliki data antrean. Nonaktifkan saja agar histori tetap utuh.');
        }
        $barber->delete();
        return redirect()->route('admin.barbers.index')
            ->with('success', 'Barber berhasil dihapus.');
    }
}
