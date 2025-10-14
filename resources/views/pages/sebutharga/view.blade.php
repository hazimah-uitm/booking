@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Sebut Harga</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('sebutharga.index') }}">Senarai Sebut Harga</a></li>
                <li class="breadcrumb-item active" aria-current="page">Maklumat Sebut Harga</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('sebutharga.form', ['id' => $sebutharga['id'] ?? 1]) }}" class="btn btn-primary mt-2 mt-lg-0">
            Kemaskini
        </a>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Maklumat Sebut Harga</h6>
<hr />

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-4">
            <tr>
                <th style="width:25%">Nama Program</th>
                <td>{{ $tempahan['nama_program'] ?? 'Festival Kampus' }}</td>
            </tr>
            <tr>
                <th>Tarikh</th>
                <td>
                    {{ \Carbon\Carbon::parse($tempahan['tarikh_mula'])->format('d/m/Y') }}
                    –
                    {{ \Carbon\Carbon::parse($tempahan['tarikh_tamat'])->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <th>Kampus / Ruang</th>
                <td>{{ $tempahan['kampus_nama'] ?? 'UiTM Samarahan' }} — {{ $tempahan['ruang_nama'] ?? 'Pelbagai Ruang' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge bg-success">{{ $tempahan['status'] ?? 'Diluluskan' }}</span></td>
            </tr>
        </table>

        <h6 class="fw-bold mb-3">Senarai Kadar Sewa & Jumlah</h6>
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width:60%">FASILITI & PRASARANA</th>
                    <th class="text-center" style="width:20%">KADAR SEWA (RM)</th>
                    <th class="text-center" style="width:20%">JUMLAH (RM)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>(A) Ruang</strong><br>Dewan Jubli, Kampus Samarahan (1–5 Mei 2025)<br><small>Kadar termasuk: Penghawa dingin, Rostrum & capaian internet</small></td>
                    <td class="text-end">2,500.00 / hari x 5 hari</td>
                    <td class="text-end">12,500.00</td>
                </tr>
                <tr>
                    <td>Kerusi banquet</td>
                    <td class="text-end">4 / buah x 100 buah</td>
                    <td class="text-end">400.00</td>
                </tr>
                <tr>
                    <td>Sofa 4 settee (4 Mei 2025, malam)</td>
                    <td class="text-end">7 / buah x 4 buah</td>
                    <td class="text-end">28.00</td>
                </tr>
                <tr>
                    <td><em>* PA system & mikrofon disediakan kontraktor PSN</em></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Bilik Perdana (3 & 4 Mei 2025)</td>
                    <td class="text-end">315 / hari x 1.5 hari</td>
                    <td class="text-end">472.50</td>
                </tr>
                <tr>
                    <td>Pusat Pelajar (1–5 Mei 2025)</td>
                    <td class="text-end">1,200 / hari x 5 hari</td>
                    <td class="text-end">6,000.00</td>
                </tr>
                <tr>
                    <td><strong>Jumlah Keseluruhan</strong></td>
                    <td></td>
                    <td class="text-end fw-bold">19,400.50</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
