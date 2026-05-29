<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->view('admin.users._index-content', compact('users'));
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // DEFAULT FOTO
        $fotoPath = null;

        // UPLOAD FOTO
        if ($request->hasFile('foto')) {

            $file = $request->file('foto');

            $filename = time() . '_' . uniqid() . '.' . $file->extension();

            // PASTIKAN FOLDER ADA
            $destination = public_path('storage/users');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            // MOVE FILE
            $file->move($destination, $filename);

            // SIMPAN PATH DB
            $fotoPath = 'users/' . $filename;
        }

        // CREATE USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => $fotoPath,
        ]);

        // ROLE
        $user->assignRole($request->role);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // UPDATE DATA
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // PASSWORD OPTIONAL
        if ($request->filled('password')) {

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // UPDATE FOTO
        if ($request->hasFile('foto')) {

            // HAPUS FOTO LAMA
            if ($user->foto) {

                $oldPath = public_path('storage/' . $user->foto);

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // FILE BARU
            $file = $request->file('foto');

            $filename = time() . '_' . uniqid() . '.' . $file->extension();

            $destination = public_path('storage/users');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);

            // UPDATE DB
            $user->update([
                'foto' => 'users/' . $filename,
            ]);
        }

        // UPDATE ROLE
        $user->syncRoles([$request->role]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // HAPUS FOTO
        if ($user->foto) {

            $fotoPath = public_path('storage/' . $user->foto);

            if (File::exists($fotoPath)) {
                File::delete($fotoPath);
            }
        }

        // DELETE USER
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
}
