<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecializationRequest;
use App\Http\Requests\UpdateSpecializationRequest;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index(Request $request)
    {
        $query = Specialization::query();

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->query('status') === 'inactive') {
            $query->where('is_active', false);
        } elseif ($request->query('status') === 'active') {
            $query->where('is_active', true);
        }

        $specializations = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('specializations.index', compact('specializations', 'search'));
    }

    public function create()
    {
        return view('specializations.create');
    }

    public function store(StoreSpecializationRequest $request)
    {
        Specialization::create($request->validated());

        return redirect()->route('specializations.index')
            ->with('success', 'Specjalizacja dodana.');
    }

    public function show(Specialization $specialization)
    {
        $specialization->load('doctors.user');
        return view('specializations.show', compact('specialization'));
    }

    public function edit(Specialization $specialization)
    {
        return view('specializations.edit', compact('specialization'));
    }

    public function update(UpdateSpecializationRequest $request, Specialization $specialization)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $specialization->update($data);

        return redirect()->route('specializations.index')
            ->with('success', 'Specjalizacja zaktualizowana.');
    }

    public function destroy(Specialization $specialization)
    {
        $this->authorize('delete', $specialization);
        $specialization->update(['is_active' => false]);
        $specialization->delete();

        return redirect()->route('specializations.index')
            ->with('success', 'Specjalizacja dezaktywowana.');
    }
}
