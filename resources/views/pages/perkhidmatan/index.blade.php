@extends('layouts.master')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Perkhidmatan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Senarai Perkhidmatan</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('perkhidmatan.form') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
            <i class="bx bxs-plus-square"></i> Tambah Perkhidmatan
        </a>
    </div>
</div>
<!--end breadcrumb-->

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<h6 class="mb-0 text-uppercase">Senarai Perkhidmatan</h6>
<hr/>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>PTJ</th>
            <th>Status</th>
            <th style="width:190px">Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($perkhidmatanList as $i => $p)
          <tr>
            <td>{{ $i+1 }}</td>
            <td class="fw-semibold">{{ $p['nama'] }}</td>
            <td>{{ $p['ptj_nama'] }}</td>
            <td>
              @if( (string)($p['status']) === '1' || $p['status'] === 'Aktif')
                <span class="badge bg-success">Aktif</span>
              @else
                <span class="badge bg-danger">Tidak Aktif</span>
              @endif
            </td>
            <td>
              <a href="{{ route('perkhidmatan.view', $p['id']) }}" class="btn btn-sm btn-primary">
                <i class="bx bx-show"></i> Papar
              </a>
              <a href="{{ route('perkhidmatan.form', ['id'=>$p['id']]) }}" class="btn btn-sm btn-info">
                <i class="bx bxs-edit"></i> Kemaskini
              </a>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted">Tiada rekod.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
