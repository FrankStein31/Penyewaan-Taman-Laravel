<?php

namespace App\Http\Controllers;

use App\Models\Taman;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Taman::query();

        // Filter untuk pencarian yang lebih baik
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhereRaw('CAST(kapasitas AS CHAR) like ?', ['%' . $search . '%'])
                  ->orWhereRaw('CAST(harga_per_hari AS CHAR) like ?', ['%' . $search . '%']);
            });
        }

        // Filter berdasarkan range harga
        if ($request->filled('harga_min')) {
            $hargaMin = str_replace('.', '', $request->harga_min);
            $query->where('harga_per_hari', '>=', $hargaMin);
        }
        if ($request->filled('harga_max')) {
            $hargaMax = str_replace('.', '', $request->harga_max);
            $query->where('harga_per_hari', '<=', $hargaMax);
        }

        // Filter berdasarkan kapasitas
        if ($request->filled('kapasitas_min')) {
            $query->where('kapasitas', '>=', $request->kapasitas_min);
        }

        // Filter berdasarkan fasilitas
        if ($request->filled('fasilitas')) {
            foreach($request->fasilitas as $fasilitas) {
                $query->whereJsonContains('fasilitas', $fasilitas);
            }
        }

        // Filter non-admin hanya melihat yang tersedia
        if (!auth()->user()->isAdmin()) {
            $query->where('status', true);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('nama', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'harga_asc':
                $query->orderBy('harga_per_hari', 'asc');
                break;
            case 'harga_desc':
                $query->orderBy('harga_per_hari', 'desc');
                break;
            case 'kapasitas_asc':
                $query->orderBy('kapasitas', 'asc');
                break;
            case 'kapasitas_desc':
                $query->orderBy('kapasitas', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // Ambil jumlah data per halaman dari request, default 10
        $perPage = $request->input('per_page', 10);
        
        $taman = $query->paginate($perPage)->withQueryString();
        $allFasilitas = Fasilitas::orderBy('nama_fasilitas')->pluck('nama_fasilitas');

        if ($request->ajax()) {
            return view('taman.list', compact('taman'))->render();
        }

        return view('taman.index', compact('taman', 'allFasilitas'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();
        return view('taman.create', compact('fasilitas'));
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
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $path = $gambar->storeAs('taman', time() . '_' . $gambar->getClientOriginalName(), 'public');
            $data['gambar'] = $path;
        }

        $taman = Taman::create($data);

        // Upload foto-foto tambahan jika ada
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->storeAs('taman', time() . '_' . $foto->getClientOriginalName(), 'public');
                $taman->fotos()->create([
                    'foto' => $path
                ]);
            }
        }

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil ditambahkan');
    }

    public function show(Taman $taman)
    {
        // Ambil data fasilitas yang dipilih dengan fotonya
        $fasilitas = Fasilitas::whereIn('nama_fasilitas', $taman->fasilitas)->get();
        return view('taman.show', compact('taman', 'fasilitas'));
    }

    public function edit(Taman $taman)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();
        return view('taman.edit', compact('taman', 'fasilitas'));
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
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_fotos' => 'nullable|array',
            'delete_fotos.*' => 'exists:taman_fotos,id',
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

        // Hapus foto yang dicentang untuk dihapus
        if ($request->has('delete_fotos')) {
            foreach ($request->delete_fotos as $fotoId) {
                $foto = $taman->fotos()->find($fotoId);
                if ($foto) {
                    Storage::disk('public')->delete($foto->foto);
                    $foto->delete();
                }
            }
        }

        // Upload foto-foto baru jika ada
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->storeAs('taman', time() . '_' . $foto->getClientOriginalName(), 'public');
                $taman->fotos()->create([
                    'foto' => $path
                ]);
            }
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

        // Hapus gambar utama jika ada
        if ($taman->gambar) {
            Storage::disk('public')->delete($taman->gambar);
        }

        // Hapus semua foto terkait
        foreach ($taman->fotos as $foto) {
            Storage::disk('public')->delete($foto->foto);
        }
        $taman->fotos()->delete();

        $taman->delete();

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil dihapus');
    }
}