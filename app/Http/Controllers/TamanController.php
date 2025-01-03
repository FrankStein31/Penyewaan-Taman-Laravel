<?php

namespace App\Http\Controllers;

use App\Models\Taman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TamanController extends Controller
{
    public function index()
    {
        $taman = Taman::latest()->paginate(10);
        return view('taman.index', compact('taman'));
    }

    public function create()
    {
        return view('taman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $path = $gambar->store('public/taman');
            $data['gambar'] = str_replace('public/', '', $path);
        }

        Taman::create($data);

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil ditambahkan');
    }

    public function edit(Taman $taman)
    {
        return view('taman.edit', compact('taman'));
    }

    public function update(Request $request, Taman $taman)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($taman->gambar) {
                Storage::delete('public/' . $taman->gambar);
            }

            $gambar = $request->file('gambar');
            $path = $gambar->store('public/taman');
            $data['gambar'] = str_replace('public/', '', $path);
        }

        $taman->update($data);

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil diupdate');
    }

    public function destroy(Taman $taman)
    {
        if ($taman->gambar) {
            Storage::delete('public/' . $taman->gambar);
        }
        
        $taman->delete();

        return redirect()->route('taman.index')
            ->with('success', 'Taman berhasil dihapus');
    }
}