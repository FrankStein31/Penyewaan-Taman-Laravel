<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenyewaanController extends Controller
{
    public function index()
    {
        return view('penyewaan.index');
    }

    public function create()
    {
        return view('penyewaan.create');
    }

    public function store(Request $request)
    {
        // Logic untuk menyimpan penyewaan
    }

    public function show($id)
    {
        return view('penyewaan.show');
    }

    public function edit($id)
    {
        return view('penyewaan.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic untuk update penyewaan
    }

    public function destroy($id)
    {
        // Logic untuk hapus penyewaan
    }
} 