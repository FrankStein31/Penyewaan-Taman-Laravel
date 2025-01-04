<?php

namespace App\Http\Controllers;

use App\Models\Taman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Taman::query();

        // Filter untuk pencarian
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan range harga
        if ($request->filled(['harga_min', 'harga_max'])) {
            $query->whereBetween('harga_per_hari', [$request->harga_min, $request->harga_max]);
        }

        // Filter berdasarkan kapasitas
        if ($request->filled('kapasitas_min')) {
            $query->where('kapasitas', '>=', $request->kapasitas_min);
        }

        // Filter berdasarkan fasilitas
        if ($request->filled('fasilitas')) {
            $query->where(function($q) use ($request) {
                foreach($request->fasilitas as $fasilitas) {
                    $q->whereJsonContains('fasilitas', $fasilitas);
                }
            });
        }

        // Filter berdasarkan status (untuk non-admin selalu true)
        if (!auth()->user()->isAdmin()) {
            $query->where('status', true);
        }

        $taman = $query->latest()->paginate(10);

        // Ambil semua fasilitas unik untuk dropdown
        $allFasilitas = Taman::pluck('fasilitas')
            ->flatten()
            ->unique()
            ->values()
            ->all();

        return view('taman.index', compact('taman', 'allFasilitas'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        return view('taman.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'harga_per_hari' => 'required|numeric|min:0',
            'fasilitas' => 'required|array',
            'fasilitas.*' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $path = $gambar->storeAs('taman', time() . '_' . $gambar->getClientOriginalName(), 'public');
            $data['gambar'] = $path;
        }

        Taman::create($data);

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil ditambahkan');
    }

    public function show(Taman $taman)
    {
        return view('taman.show', compact('taman'));
    }

    public function edit(Taman $taman)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        return view('taman.edit', compact('taman'));
    }

    public function update(Request $request, Taman $taman)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'harga_per_hari' => 'required|numeric|min:0',
            'fasilitas' => 'required|array',
            'fasilitas.*' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($taman->gambar) {
                Storage::disk('public')->delete($taman->gambar);
            }
            
            $gambar = $request->file('gambar');
            $path = $gambar->storeAs('taman', time() . '_' . $gambar->getClientOriginalName(), 'public');
            $data['gambar'] = $path;
        }

        $taman->update($data);

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil diperbarui');
    }

    public function destroy(Taman $taman)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        // Hapus gambar jika ada
        if ($taman->gambar) {
            Storage::disk('public')->delete($taman->gambar);
        }

        $taman->delete();

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil dihapus');
    }
}