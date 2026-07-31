<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::withCount(['barbers', 'services'])->latest()->paginate(10);
        return view('admin.branches.index', compact('branches'));
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
            'phone'        => 'nullable|string|max:20',
            'city'         => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'open_time'    => 'required|string',
            'close_time'   => 'required|string',
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
            'phone'        => 'nullable|string|max:20',
            'city'         => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'open_time'    => 'required|string',
            'close_time'   => 'required|string',
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
        $branch->delete();
        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
