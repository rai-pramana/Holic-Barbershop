<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarberController extends Controller
{
    public function index(): View
    {
        $barbers = Barber::with('branch')->latest()->paginate(15);
        return view('admin.barbers.index', compact('barbers'));
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
            'phone'        => 'nullable|string|max:20',
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
            'phone'        => 'nullable|string|max:20',
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
        $barber->delete();
        return redirect()->route('admin.barbers.index')
            ->with('success', 'Barber berhasil dihapus.');
    }
}
