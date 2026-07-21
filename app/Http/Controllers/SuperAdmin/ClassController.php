<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::withCount('students')->get();
        return view('superadmin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
            'grade' => 'required|in:X,XI,XII',
            'major' => 'required|string',
        ]);

        ClassModel::create($request->all());

        return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        ClassModel::destroy($id);
        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}