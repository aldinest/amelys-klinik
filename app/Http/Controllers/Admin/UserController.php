<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(Request $request)
    {
         $users = User::when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'role'  => 'required|in:admin,pengurus,pasien',
        ]);

        User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'role'             => $request->role,
            'password'         => bcrypt('password123'),
            'password_default' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,pengurus,pasien',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->only('name', 'email', 'role');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
            $data['password_default'] = false;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function resetPassword(User $user)
    {
        $user->update([
            'password'         => bcrypt('password123'),
            'password_default' => true,
        ]);

        return back()->with('success', 'Password direset ke default');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

}
