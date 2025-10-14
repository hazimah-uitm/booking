@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
  <div class="breadcrumb-title pe-3">Pengurusan Perkhidmatan</div>
  <div class="ps-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 p-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('perkhidmatan.index') }}">Senarai Perkhidmatan</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Perkhidmatan</li>
      </ol>
    </nav>
  </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">{{ $str_mode }} Perkhidmatan</h6>
<hr/>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ $save_route }}">
      {{ csrf_field() }}

      <div class="mb-3">
        <label class="form-label">Nama Perkhidmatan</label>
        <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
               value="{{ old('nama', $perkhidmatan['nama'] ?? '') }}" placeholder="Contoh: PA System">
        @if($errors->has('nama'))
          <div class="invalid-feedback">
            @foreach($errors->get('nama') as $e) {{ $e }} @endforeach
          </div>
        @endif
      </div>

      <div class="mb-3">
        <label class="form-label">PTJ</label>
        <select name="ptj_id" class="form-select">
          <option value="">-- Pilih PTJ --</option>
          @foreach($ptjOptions as $pid => $pnama)
            <option value="{{ $pid }}" {{ (string)old('ptj_id', $perkhidmatan['ptj_id'] ?? '') === (string)$pid ? 'selected' : '' }}>
              {{ $pnama }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label d-block">Status</label>
        @php $st = old('status', $perkhidmatan['status'] ?? 1); @endphp
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" id="status_aktif" name="status" value="1"
                 {{ (string)$st === '1' || $st === 'Aktif' ? 'checked' : '' }}>
          <label class="form-check-label" for="status_aktif">Aktif</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" id="status_tidak" name="status" value="0"
                 {{ (string)$st === '0' || $st === 'Tidak Aktif' ? 'checked' : '' }}>
          <label class="form-check-label" for="status_tidak">Tidak Aktif</label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
      <a href="{{ route('perkhidmatan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
@endsection
