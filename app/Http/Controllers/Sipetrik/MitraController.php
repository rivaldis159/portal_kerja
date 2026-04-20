<?php

namespace App\Http\Controllers\Sipetrik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Mitra::with(['district', 'village']);

        if ($request->filled('district_id')) {
            $query->where('mitras.district_id', $request->district_id);
        }
        if ($request->filled('village_id')) {
            $query->where('mitras.village_id', $request->village_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('sobat_id', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'location_default');
        $sortDirection = $request->get('direction', 'asc');

        if ($sortField === 'location_default') {
            $query->leftJoin('districts', 'mitras.district_id', '=', 'districts.id')
                  ->leftJoin('villages', 'mitras.village_id', '=', 'villages.id')
                  ->orderBy('districts.name', 'asc')
                  ->orderBy('villages.name', 'asc')
                  ->orderBy('mitras.nama', 'asc')
                  ->select('mitras.*');
        } elseif ($sortField === 'district_name') {
            $query->leftJoin('districts', 'mitras.district_id', '=', 'districts.id')
                  ->orderBy('districts.name', $sortDirection)
                  ->select('mitras.*');
        } elseif ($sortField === 'village_name') {
            $query->leftJoin('villages', 'mitras.village_id', '=', 'villages.id')
                  ->orderBy('villages.name', $sortDirection)
                  ->select('mitras.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $mitras = $query->paginate(20)->withQueryString();

        $districts = \App\Models\District::orderBy('name')->get();
        $villages = collect();
        if ($request->filled('district_id')) {
            $villages = \App\Models\Village::where('district_id', $request->district_id)->orderBy('name')->get();
        }

        return view('sipetrik.mitras.index', compact('mitras', 'districts', 'villages'));
    }
    
    public function import(\Illuminate\Http\Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\MitraImport, $request->file('file'));
            return back()->with('success', 'Data Mitra berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $mitra = \App\Models\Mitra::with(['district', 'village'])->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('sipetrik.mitras.partials.detail-modal', compact('mitra'))->render()
            ]);
        }

        return view('sipetrik.mitras.show', compact('mitra'));
    }
}
