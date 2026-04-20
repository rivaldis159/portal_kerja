<?php

namespace App\Http\Controllers\Sipetrik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenawaranKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = \App\Models\PenawaranKerja::with(['mitra', 'team'])->latest()->paginate(20);
        return view('sipetrik.penawaran.index', compact('contracts'));
    }

    public function create()
    {
        $teams = \App\Models\Team::all();
        $mitras = \App\Models\Mitra::orderBy('nama')->get();
        $activities = \App\Models\Activity::orderBy('name')->get();
        return view('sipetrik.penawaran.create', compact('teams', 'mitras', 'activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'mitra_id' => 'required|exists:mitras,id',
            'activity_id' => 'required|exists:activities,id',
            'kegiatan' => 'required|string',
            'uraian_tugas' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'volume' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $validated['nilai_kontrak'] = $validated['volume'] * $validated['harga_satuan'];
        $validated['status'] = 'offered';
        $validated['created_by'] = auth()->id();

        \App\Models\PenawaranKerja::create($validated);

        return redirect()->route('sipetrik.penawaran-kerja.index')->with('success', 'Penawaran kerja berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contract = \App\Models\PenawaranKerja::with(['mitra', 'team', 'activity', 'penilaian', 'createdBy'])->findOrFail($id);
        
        if (request()->ajax()) {
            return view('sipetrik.penawaran.partials.detail-modal', compact('contract'));
        }
        
        return view('sipetrik.penawaran.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contract = \App\Models\PenawaranKerja::findOrFail($id);
        $teams = \App\Models\Team::all();
        $mitras = \App\Models\Mitra::orderBy('nama')->get();
        $activities = \App\Models\Activity::orderBy('name')->get();
        
        return view('sipetrik.penawaran.edit', compact('contract', 'teams', 'mitras', 'activities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'mitra_id' => 'required|exists:mitras,id',
            'activity_id' => 'required|exists:activities,id',
            'kegiatan' => 'required|string',
            'uraian_tugas' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'volume' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $validated['nilai_kontrak'] = $validated['volume'] * $validated['harga_satuan'];
        
        $contract = \App\Models\PenawaranKerja::findOrFail($id);
        $contract->update($validated);

        return redirect()->route('sipetrik.penawaran-kerja.show', $contract->id)->with('success', 'Kontrak berhasil diperbarui.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:offered,accepted,completed,cancelled'
        ]);
        
        $contract = \App\Models\PenawaranKerja::findOrFail($id);
        $contract->update(['status' => $request->status]);
        
        return back()->with('success', 'Status kontrak diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contract = \App\Models\PenawaranKerja::findOrFail($id);
        $contract->delete();
        
        return redirect()->route('sipetrik.penawaran-kerja.index')->with('success', 'Kontrak berhasil dihapus.');
    }
}
