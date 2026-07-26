<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('siswa.profile.index', compact('user'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $relativePath = 'profile-photos/' . $filename;

            // Folder penyimpanan publik langsung
            $publicDir = public_path('storage/profile-photos');
            $storageDir = storage_path('app/public/profile-photos');

            if (!file_exists($publicDir)) {
                mkdir($publicDir, 0777, true);
            }
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0777, true);
            }

            // Pindahkan file langsung ke public storage
            $file->move($publicDir, $filename);

            // Backup copy ke storage app
            @copy($publicDir . '/' . $filename, $storageDir . '/' . $filename);

            // Update database
            $user->update(['foto' => $relativePath]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}