<?php

namespace App\Http\Controllers\Sipetrik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = \App\Models\PenawaranKerja::with(['mitra', 'team', 'penilaian'])
                    ->orderBy('team_id')
                    ->orderBy('activity_id')
                    ->latest()
                    ->paginate(20);
                    
        return view('sipetrik.penilaian.index', compact('contracts'));
    }

    public function create(Request $request)
    {
        $contract_id = $request->input('contract_id');
        if (!$contract_id) {
            return redirect()->route('sipetrik.penilaian.index')->with('error', 'Pilih kontrak terlebih dahulu.');
        }

        $contract = \App\Models\PenawaranKerja::findOrFail($contract_id);
        if ($contract->penilaian) {
            return redirect()->route('sipetrik.penilaian.index')->with('warning', 'Kontrak ini sudah dinilai.');
        }
        
        return view('sipetrik.penilaian.create', compact('contract'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'q1_kualitas' => 'required|integer|min:1|max:5',
            'q2_inisiatif' => 'required|integer|min:1|max:5',
            'q3_kerjasama' => 'required|integer|min:1|max:5',
            'q4_integritas' => 'required|integer|min:1|max:5',
            'q5_keandalan' => 'required|integer|min:1|max:5',
            'rekomendasi' => 'nullable|boolean',
            'alasan_rekomendasi' => 'nullable|string',
        ]);

        $total = $validated['q1_kualitas'] + $validated['q2_inisiatif'] + $validated['q3_kerjasama'] + 
                 $validated['q4_integritas'] + $validated['q5_keandalan'];
        $validated['average_score'] = $total / 5;
        $validated['rated_by'] = auth()->id();
        $validated['rekomendasi'] = $request->has('rekomendasi');

        \App\Models\PenilaianKinerja::create($validated);
        
        $contract = \App\Models\PenawaranKerja::find($validated['contract_id']);
        if($contract->status != 'completed') {
            $contract->update(['status' => 'completed']);
        }

        return redirect()->route('sipetrik.penilaian.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
