<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ==================== KELOLA KASIR ====================
    public function indexKasir()
    {
        $kasirs = User::where('role', 'kasir')->latest()->get();
        return view('admin.kasir.index', compact('kasirs'));
    }

    public function storeKasir(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => 'kasir',
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun Kasir berhasil ditambahkan!');
    }

    public function resetPasswordKasir(Request $request, int $id)
    {
        $request->validate(['password' => 'required|string|min:6']);
        $kasir = User::findOrFail($id);
        $kasir->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password kasir berhasil diperbarui!');
    }

    public function toggleStatusKasir(int $id)
    {
        $kasir = User::findOrFail($id);
        $kasir->update(['is_active' => !$kasir->is_active]);

        return redirect()->back()->with('success', 'Status kasir berhasil diubah!');
    }

    // ==================== KELOLA SISWA ====================
    public function indexSiswa(Request $request)
    {
        $query = User::with('classModel')->where('role', 'siswa');

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn_nip', 'like', "%{$search}%");
            });
        }

        // Filter Kelas/Jurusan
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $students */
        $students = $query->latest()->paginate(15);
        $students->withQueryString();

        $classes = ClassModel::all();

        return view('admin.siswa.index', compact('students', 'classes'));
    }

    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nisn_nip' => 'required|string|max:50|unique:users,nisn_nip',
            'name'     => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nisn_nip'  => $request->nisn_nip,
            'username'  => $request->nisn_nip, // Set username sama dengan NISN
            'name'      => $request->name,
            'class_id'  => $request->class_id,
            'password'  => Hash::make($request->password),
            'role'      => 'siswa',
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function resetPasswordSiswa(Request $request, int $id)
    {
        $request->validate(['password' => 'required|string|min:6']);
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $siswa->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password siswa berhasil diperbarui!');
    }

    public function destroySiswa(int $id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $siswa->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus!');
    }

    // ==================== IMPOR SISWA (CSV/EXCEL) ====================
    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');

        if (($handle = fopen($file->getRealPath(), 'r')) !== FALSE) {
            // Lewati baris header CSV (nisn, name, class_id, password)
            fgetcsv($handle, 1000, ',');

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (!empty($data[0])) {
                    User::updateOrCreate(
                        ['nisn_nip' => trim($data[0])],
                        [
                            'username'  => trim($data[0]),
                            'name'      => $data[1] ?? 'Siswa',
                            'class_id'  => !empty($data[2]) ? $data[2] : null,
                            'password'  => Hash::make(!empty($data[3]) ? $data[3] : '12345678'),
                            'role'      => 'siswa',
                            'is_active' => true,
                        ]
                    );
                }
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', 'Data siswa berhasil diimpor!');
    }
}