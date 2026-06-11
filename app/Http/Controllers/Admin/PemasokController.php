<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemasok::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where('nama_pemasok', 'like', "%{$q}%")
                ->orWhere('kode_pemasok', 'like', "%{$q}%");
        }

        $pemasoks = $query->latest()->paginate(10)->withQueryString();
        return view('admin.pemasok.index', compact('pemasoks'));
    }

    public function create()
    {
        return view('admin.pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pemasok' => 'required|string|unique:pemasoks,kode_pemasok',
            'nama_pemasok' => 'required|string|max:255',
            'alamat'       => 'nullable|string',
            'telepon'      => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'nama_kontak'  => 'nullable|string|max:255',
        ]);

        Pemasok::create($request->all());

        return redirect()->route('admin.pemasok.index')
            ->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function show(Pemasok $pemasok)
    {
        $pemasok->load('barangMasuks');
        return view('admin.pemasok.show', compact('pemasok'));
    }

    public function edit(Pemasok $pemasok)
    {
        return view('admin.pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'kode_pemasok' => 'required|string|unique:pemasoks,kode_pemasok,' . $pemasok->id,
            'nama_pemasok' => 'required|string|max:255',
            'alamat'       => 'nullable|string',
            'telepon'      => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'nama_kontak'  => 'nullable|string|max:255',
        ]);

        $pemasok->update($request->all());

        return redirect()->route('admin.pemasok.index')
            ->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok)
    {
        if ($pemasok->barangMasuks()->exists()) {
            return back()->with('error', 'Pemasok tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $pemasok->delete();

        return redirect()->route('admin.pemasok.index')
            ->with('success', 'Pemasok berhasil dihapus.');
    }
}
