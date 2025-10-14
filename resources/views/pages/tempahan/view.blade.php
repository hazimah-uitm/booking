@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
  <div class="breadcrumb-title pe-3">Tempahan Ruang</div>
  <div class="ps-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 p-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('tempahan.index') }}">Senarai Tempahan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Maklumat Tempahan</li>
      </ol>
    </nav>
  </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Maklumat Tempahan</h6>
<hr/>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <table class="table table-borderless">
          <tr>
            <th class="w-25">ID</th>
            <td>{{ $tempahan['id'] }}</td>
          </tr>
          <tr>
            <th>Nama Program</th>
            <td>{{ $tempahan['nama_program'] }}</td>
          </tr>
          <tr>
            <th>Kampus</th>
            <td>{{ $tempahan['kampus_nama'] }}</td>
          </tr>
          <tr>
            <th>Ruang</th>
            <td>{{ $tempahan['ruang_nama'] }} ({{ $tempahan['ruang_kod'] }})</td>
          </tr>
          <tr>
            <th>Tujuan</th>
            <td>{{ $tempahan['tujuan'] }}</td>
          </tr>
          <tr>
            <th>Tarikh & Masa</th>
            <td>
              {{ \Carbon\Carbon::parse($tempahan['tarikh_mula'])->format('d/m/Y h:i A') }}
              &nbsp;–&nbsp;
              {{ \Carbon\Carbon::parse($tempahan['tarikh_tamat'])->format('d/m/Y h:i A') }}
            </td>
          </tr>
          <tr>
            <th>Perkhidmatan</th>
            <td>
              @if(!empty($tempahan['perkhidmatan']))
                {{ implode(', ', $tempahan['perkhidmatan']) }}
              @else
                -
              @endif
            </td>
          </tr>
          <tr>
            <th>Status</th>
            <td><span class="badge bg-warning text-dark">{{ $tempahan['status'] }}</span></td>
          </tr>
        </table>
        <a href="{{ route('tempahan.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>
@endsection
