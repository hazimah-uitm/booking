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
                <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Ruang</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">{{ $str_mode }} Ruang</h6>
<hr />

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ $save_route }}">
            {{ csrf_field() }}

            <div class="mb-3">
                <label class="form-label">Nama Ruang</label>
                <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                       value="{{ old('nama', $ruang['nama'] ?? '') }}" placeholder="Contoh: Dewan Besar">
                @if($errors->has('nama'))
                    <div class="invalid-feedback">
                        @foreach($errors->get('nama') as $e) {{ $e }} @endforeach
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Kampus</label>
                <select name="kampus_id" class="form-select">
                    <option value="">-- Pilih Kampus --</option>
                    @foreach($kampusOptions as $kid => $knama)
                        <option value="{{ $kid }}" {{ (string)old('kampus_id', $ruang['kampus_id'] ?? '') === (string)$kid ? 'selected' : '' }}>
                            {{ $knama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Pemilik</label>
                <input type="text" name="pemilik" class="form-control"
                       value="{{ old('pemilik', $ruang['pemilik'] ?? '') }}" placeholder="Contoh: HEP / Fakulti Sains">
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Status</label>
                @php $st = old('status', $ruang['status'] ?? 1); @endphp
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="status_aktif" name="status" value="1" {{ (string)$st === '1' || $st === 'Aktif' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_aktif">Aktif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="status_tidak" name="status" value="0" {{ (string)$st === '0' || $st === 'Tidak Aktif' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_tidak">Tidak Aktif</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            <a href="{{ route('ruang.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
