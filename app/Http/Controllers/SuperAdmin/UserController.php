<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('lembaga');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('lembaga_id')) {
            $query->where('lembaga_id', $request->input('lembaga_id'));
        }

        $users = $query->paginate(10)->withQueryString();
        $lembagaList = Lembaga::aktif()->get();

        return view('super-admin.users.index', compact('users', 'lembagaList'));
    }

    public function create()
    {
        $lembagaList = Lembaga::aktif()->get();
        return view('super-admin.users.create', compact('lembagaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:' . User::ROLE_SUPER_ADMIN . ',' . User::ROLE_ADMIN_LEMBAGA,
            'lembaga_id' => [
                'nullable',
                Rule::requiredIf($request->input('role') === User::ROLE_ADMIN_LEMBAGA),
                'exists:lembaga,id'
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'lembaga_id.required' => 'Lembaga wajib dipilih untuk Admin Lembaga.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('super-admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return redirect()->route('super-admin.users.index');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $lembagaList = Lembaga::aktif()->get();
        return view('super-admin.users.edit', compact('user', 'lembagaList'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:' . User::ROLE_SUPER_ADMIN . ',' . User::ROLE_ADMIN_LEMBAGA,
            'lembaga_id' => [
                'nullable',
                Rule::requiredIf($request->input('role') === User::ROLE_ADMIN_LEMBAGA),
                'exists:lembaga,id'
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'lembaga_id.required' => 'Lembaga wajib dipilih untuk Admin Lembaga.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // If switching to super admin, clear lembaga_id
        if ($validated['role'] === User::ROLE_SUPER_ADMIN) {
            $validated['lembaga_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('super-admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('super-admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('digidas2025');
        $user->save();

        return back()->with('success', "Password untuk pengguna {$user->name} berhasil direset menjadi 'digidas2025'.");
    }
}
