@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manajemen Cabang</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Cabang</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Provinsi</th>
                <th>Kode Cabang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cabangs as $cabang)
            <tr>
                <td>{{ $cabang->id }}</td>
                <td>{{ $cabang->nama_cabang }}</td>
                <td>{{ $cabang->alamat }}</td>
                <td>{{ $cabang->kota }}</td>
                <td>{{ $cabang->provinsi }}</td>
                <td>{{ $cabang->kode_cabang }}</td>
                <td>
                    <a href="{{ route('cabang.edit', $cabang->id) }}" class="btn btn-primary btn-sm">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
