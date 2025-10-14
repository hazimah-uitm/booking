@extends('layouts.master')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Ruang</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Senarai Ruang</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('ruang.form') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
            <i class="bx bxs-plus-square"></i> Tambah Ruang
        </a>
    </div>
</div>
<!--end breadcrumb-->

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<h6 class="mb-0 text-uppercase">Senarai Ruang</h6>
<hr />

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Ruang</th>
                        <th>Kampus</th>
                        <th>Pemilik</th>
                        <th>Status</th>
                        <th style="width:190px">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangList as $i => $r)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td class="fw-semibold">{{ $r['nama'] }}</td>
                            <td>{{ $r['kampus_nama'] }}</td>
                            <td>{{ $r['pemilik'] }}</td>
                            <td>
                                @if(($r['status'] ?? '') === 'Aktif' || ($r['status'] ?? 0) == 1)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ruang.view', $r['id']) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-show"></i> Papar
                                </a>
                                <a href="{{ route('ruang.form', ['id'=>$r['id']]) }}" class="btn btn-sm btn-info">
                                    <i class="bx bxs-edit"></i> Kemaskini
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Tiada rekod.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
