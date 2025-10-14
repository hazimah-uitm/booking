@extends('layouts.master')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Sebut Harga</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Senarai Sebut Harga</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('sebutharga.form') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
            <i class="bx bxs-plus-square"></i> Buat Sebut Harga
        </a>
    </div>
</div>
<!--end breadcrumb-->

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<h6 class="mb-0 text-uppercase">Senarai Sebut Harga</h6>
<hr />

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Rujukan</th>
                        <th>Program / Lokasi / Tarikh</th>
                        <th>Tajuk</th>
                        <th>Jumlah (RM)</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sebuthargaList as $i => $s)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td class="fw-semibold">{{ $s['no_rujukan'] }}</td>
                            <td>
                                <div class="fw-semibold">{{ $s['tempahan']['nama_program'] }}</div>
                                <div class="text-muted">
                                    {{ $s['tempahan']['kampus_nama'] }} — {{ $s['tempahan']['ruang_nama'] }}
                                </div>
                                <div class="text-muted">
                                    {{ \Carbon\Carbon::parse($s['tempahan']['tarikh_mula'])->format('d/m/Y') }}
                                    –
                                    {{ \Carbon\Carbon::parse($s['tempahan']['tarikh_tamat'])->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>{{ $s['tajuk'] }}</td>
                            <td class="text-end">{{ number_format($s['total'],2) }}</td>
                            <td>
                                @php $st = $s['status']; @endphp
                                @if($st==='Draf')
                                    <span class="badge bg-secondary">Draf</span>
                                @else
                                    <span class="badge bg-success">Dihantar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('sebutharga.view', $s['id']) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-show"></i> Papar
                                </a>
                                <a href="{{ route('sebutharga.form', ['id'=>$s['id']]) }}" class="btn btn-sm btn-info">
                                    <i class="bx bxs-edit"></i> Kemaskini
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Tiada sebutharga.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
