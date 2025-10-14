<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlatMusik;

class AlatMusikController extends Controller
{
    public function create()
    {
        return view('admin.tambah_alatmusik');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        AlatMusik::create([
            'nama' => $request->nama,
        ]);

        return redirect()->back()->with('success', 'Alat musik berhasil ditambahkan!');
    }
}
