<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::latest()->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas'      => 'required|string|max:50',
            'jurusan'    => 'required|string|max:100',
            'nama_kelas' => 'nullable|string|max:100',
        ]);

        // Sesuaikan input form ke nama kolom database (grade, major, class_name)
        ClassModel::create([
            'grade'      => $request->kelas,
            'major'      => $request->jurusan,
            'class_name' => $request->nama_kelas ?? ($request->kelas . ' ' . $request->jurusan),
        ]);

        return redirect()->back()->with('success', 'Kelas & Jurusan berhasil ditambahkan!');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'kelas'      => 'required|string|max:50',
            'jurusan'    => 'required|string|max:100',
            'nama_kelas' => 'nullable|string|max:100',
        ]);

        $class = ClassModel::findOrFail($id);
        
        $class->update([
            'grade'      => $request->kelas,
            'major'      => $request->jurusan,
            'class_name' => $request->nama_kelas ?? ($request->kelas . ' ' . $request->jurusan),
        ]);

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $class = ClassModel::findOrFail($id);
        $class->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}