@extends('layout')

@section('title', 'Manajemen User')

@section('content')
<div class="container mt-4">
    <h2 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Daftar User (Admin Cabang & User)</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Cabang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $user)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->label ?? '-' }}</td>
                <td>{{ $user->cabang->nama_cabang ?? '-' }}</td>
                <td>
                    <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus akun ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                    <a href="{{ route('user-management.show', $user->id) }}" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
