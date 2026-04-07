<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    // Hanya superadmin yang boleh akses
    public function index(): View
    {
        $users = User::with('role')
            ->whereHas('role', function($q) {
                $q->whereIn('name', ['admin_cabang', 'user']);
            })
            ->get();
        return view('user_management.index', compact('users'));
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        if (in_array($user->role->name, ['admin_cabang', 'user'])) {
            $user->delete();
            return back()->with('success', 'Akun berhasil dihapus');
        }
        return back()->with('error', 'Tidak bisa menghapus akun ini');
    }

    public function show($id): View
    {
        $user = User::with(['role', 'cabang'])->findOrFail($id);
        return view('user_management.show', compact('user'));
    }

    public function resetPassword($id, Request $request): RedirectResponse
    {
        $user = User::findOrFail($id);
        if (!in_array($user->role->name, ['admin_cabang', 'user'])) {
            return back()->with('error', 'Tidak bisa reset password akun ini');
        }
        $request->validate([
            'password' => 'required|min:4',
        ]);
        $user->password = bcrypt($request->password);
        $user->save();
        return back()->with('success', 'Password berhasil direset');
    }
}
