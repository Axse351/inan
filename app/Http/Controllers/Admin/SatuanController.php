<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::latest()->paginate(10);
        return view('admin.satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('admin.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:satuans,nama_satuan',
        ]);

        Satuan::create($request->all());

        return redirect()->route('admin.satuan.index')
            ->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit(Satuan $satuan)
    {
        return view('admin.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:satuans,nama_satuan,' . $satuan->id,
        ]);

        $satuan->update($request->all());

        return redirect()->route('admin.satuan.index')
            ->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->barangs()->exists()) {
            return back()->with('error', 'Satuan tidak dapat dihapus karena masih digunakan oleh barang.');
        }

        $satuan->delete();

        return redirect()->route('admin.satuan.index')
            ->with('success', 'Satuan berhasil dihapus.');
    }
}
