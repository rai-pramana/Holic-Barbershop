<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::with('branch')->latest()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.services.create', compact('branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'price'            => 'required|numeric|min:0',
            'is_active'        => 'boolean',
        ]);

        Service::create([
            'branch_id'        => $request->branch_id,
            'name'             => $request->name,
            'description'      => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'price'            => $request->price,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.services.edit', compact('service', 'branches'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'price'            => 'required|numeric|min:0',
            'is_active'        => 'boolean',
        ]);

        $service->update([
            'branch_id'        => $request->branch_id,
            'name'             => $request->name,
            'description'      => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'price'            => $request->price,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
