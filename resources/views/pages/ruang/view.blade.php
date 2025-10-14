@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Ruang</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('ruang.index') }}">Senarai Ruang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Maklumat {{ $ruang['nama'] }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('ruang.form', ['id'=>$ruang['id']]) }}" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</a>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Maklumat {{ $ruang['nama'] }}</h6>
<hr />

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Nama Ruang</th>
                        <td>{{ $ruang['nama'] }}</td>
                    </tr>
                    <tr>
                        <th>Kampus</th>
                        <td>{{ $kampus_nama }}</td>
                    </tr>
                    <tr>
                        <th>Pemilik</th>
                        <td>{{ $ruang['pemilik'] }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if( (string)($ruang['status']) === '1' || $ruang['status'] === 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
