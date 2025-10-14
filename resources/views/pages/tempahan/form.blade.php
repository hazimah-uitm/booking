@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Rekod</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('rekod.tempahan.create') }}">Tempahan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Borang Pemohon</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Borang Tempahan Ruang (Pemohon)</h6>
<hr />

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
// Senarai ruang + imej (boleh pindah ke DB nanti)
$ruangList = [
    ['id'=>'dewan-seri-samarahan','name'=>'Dewan Jubli','img'=>asset('public/assets/ruang/dewan-seri-samarahan.jpg')],
    ['id'=>'dewan-kuliah','name'=>'Dewan Kuliah','img'=>asset('public/assets/ruang/dewan-kuliah.jpg')],
    ['id'=>'bilik-seminar','name'=>'Bilik Seminar','img'=>asset('public/assets/ruang/bilik-seminar.jpg')],
];
@endphp

<style>
/* gaya kad boleh pilih */
input.ruang-radio { display:none; }
label.selectable-card{
    cursor:pointer; border:2px solid #e9ecef; border-radius:.5rem; overflow:hidden; transition:.15s;
    display:block; height:100%;
}
label.selectable-card:hover{ border-color:#cfd8e3; }
input.ruang-radio:checked + label.selectable-card{
    border-color:#3b82f6; box-shadow:0 0 0 .25rem rgba(59,130,246,.25);
}
.selectable-card .card-img-top{ object-fit:cover; height:160px; }
</style>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('rekod.tempahan.store') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            {{-- ================== Maklumat Pemohon ================== --}}
            <h6 class="text-muted text-uppercase mb-3">Maklumat Pemohon</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="pemohon_nama">Nama Penuh / Organisasi</label>
                    <input type="text" id="pemohon_nama" name="pemohon_nama"
                           class="form-control {{ $errors->has('pemohon_nama') ? 'is-invalid' : '' }}"
                           value="{{ old('pemohon_nama') }}">
                    @if ($errors->has('pemohon_nama'))
                        <div class="invalid-feedback">@foreach ($errors->get('pemohon_nama') as $e) {{ $e }} @endforeach</div>
                    @endif
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="pemohon_id">No. IC / Passport</label>
                    <input type="text" id="pemohon_id" name="pemohon_id"
                           class="form-control {{ $errors->has('pemohon_id') ? 'is-invalid' : '' }}"
                           value="{{ old('pemohon_id') }}">
                    @if ($errors->has('pemohon_id'))
                        <div class="invalid-feedback">@foreach ($errors->get('pemohon_id') as $e) {{ $e }} @endforeach</div>
                    @endif
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="pemohon_phone">No. Telefon</label>
                    <input type="text" id="pemohon_phone" name="pemohon_phone"
                           class="form-control {{ $errors->has('pemohon_phone') ? 'is-invalid' : '' }}"
                           value="{{ old('pemohon_phone') }}">
                    @if ($errors->has('pemohon_phone'))
                        <div class="invalid-feedback">@foreach ($errors->get('pemohon_phone') as $e) {{ $e }} @endforeach</div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="pemohon_email">Emel</label>
                    <input type="email" id="pemohon_email" name="pemohon_email"
                           class="form-control {{ $errors->has('pemohon_email') ? 'is-invalid' : '' }}"
                           value="{{ old('pemohon_email') }}">
                    @if ($errors->has('pemohon_email'))
                        <div class="invalid-feedback">@foreach ($errors->get('pemohon_email') as $e) {{ $e }} @endforeach</div>
                    @endif
                </div>
            </div>

            <hr>

            {{-- ================== Maklumat Tempahan ================== --}}
            <h6 class="text-muted text-uppercase mb-3">Maklumat Tempahan</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="nama_program">Nama Program</label>
                    <input type="text" id="nama_program" name="nama_program"
                           class="form-control {{ $errors->has('nama_program') ? 'is-invalid' : '' }}"
                           value="{{ old('nama_program') }}" placeholder="Contoh: Seminar Inovasi 2025">
                    @if ($errors->has('nama_program'))
                        <div class="invalid-feedback">@foreach ($errors->get('nama_program') as $e) {{ $e }} @endforeach</div>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="campus">Kampus</label>
                    <select id="campus" name="campus" class="form-control {{ $errors->has('campus') ? 'is-invalid' : '' }}">
                        <option value="" disabled {{ old('campus') ? '' : 'selected' }}>Pilih Kampus</option>
                        <option {{ old('campus')=='Samarahan 1'?'selected':'' }}>Samarahan 1</option>
                        <option {{ old('campus')=='Samarahan 2'?'selected':'' }}>Samarahan 2</option>
                        <option {{ old('campus')=='Mukah'?'selected':'' }}>Mukah</option>
                    </select>
                    @if ($errors->has('campus'))
                        <div class="invalid-feedback">@foreach ($errors->get('campus') as $e) {{ $e }} @endforeach</div>
                    @endif
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label" for="tarikh_mula">Tarikh Mula</label>
                    <input type="date" id="tarikh_mula" name="tarikh_mula"
                           class="form-control {{ $errors->has('tarikh_mula') ? 'is-invalid' : '' }}"
                           value="{{ old('tarikh_mula') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="masa_mula">Masa Mula</label>
                    <input type="time" id="masa_mula" name="masa_mula"
                           class="form-control {{ $errors->has('masa_mula') ? 'is-invalid' : '' }}"
                           value="{{ old('masa_mula') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="tarikh_tamat">Tarikh Tamat</label>
                    <input type="date" id="tarikh_tamat" name="tarikh_tamat"
                           class="form-control {{ $errors->has('tarikh_tamat') ? 'is-invalid' : '' }}"
                           value="{{ old('tarikh_tamat') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="masa_tamat">Masa Tamat</label>
                    <input type="time" id="masa_tamat" name="masa_tamat"
                           class="form-control {{ $errors->has('masa_tamat') ? 'is-invalid' : '' }}"
                           value="{{ old('masa_tamat') }}">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label" for="tujuan">Tujuan / Keterangan Program</label>
                    <textarea id="tujuan" name="tujuan" rows="3"
                              class="form-control {{ $errors->has('tujuan') ? 'is-invalid' : '' }}"
                              placeholder="Ringkasan aktiviti, anggaran peserta, keperluan khas...">{{ old('tujuan') }}</textarea>
                </div>
            </div>

            {{-- ============ Pilih Ruang (Gambar) ============ --}}
            <h6 class="text-muted text-uppercase mb-3">Pilih Ruang</h6>
            <div class="row">
                @foreach($ruangList as $r)
                    <div class="col-md-4 mb-3">
                        <input class="ruang-radio" type="radio" id="ruang_{{ $r['id'] }}" name="ruang" value="{{ $r['name'] }}" {{ old('ruang')==$r['name']?'checked':'' }}>
                        <label class="selectable-card" for="ruang_{{ $r['id'] }}">
                            <img class="card-img-top" src="{{ $r['img'] }}" alt="{{ $r['name'] }}">
                            <div class="p-3">
                                <h6 class="mb-0">{{ $r['name'] }}</h6>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
            @if ($errors->has('ruang'))
                <div class="text-danger mb-3 small">@foreach ($errors->get('ruang') as $e) {{ $e }} @endforeach</div>
            @endif

            {{-- Perkhidmatan tambahan (masih dropdown biasa) --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="perkhidmatan">Perkhidmatan</label>
                    <select id="perkhidmatan" name="perkhidmatan"
                            class="form-control {{ $errors->has('perkhidmatan') ? 'is-invalid' : '' }}">
                        <option value="" disabled {{ old('perkhidmatan') ? '' : 'selected' }}>Pilih Perkhidmatan</option>
                        <option {{ old('perkhidmatan')=='PA System'?'selected':'' }}>PA System</option>
                        <option {{ old('perkhidmatan')=='Meja & Kerusi'?'selected':'' }}>Meja & Kerusi</option>
                        <option {{ old('perkhidmatan')=='Pembersihan'?'selected':'' }}>Pembersihan</option>
                        <option {{ old('perkhidmatan')=='Pengawal Keselamatan'?'selected':'' }}>Pengawal Keselamatan</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="surat">Surat Permohonan (PDF/JPG)</label>
                    <input type="file" id="surat" name="surat" class="form-control">
                </div>
            </div>

            <hr>

            {{-- ================== Pengesahan ================== --}}
            <h6 class="text-muted text-uppercase mb-2">Pengesahan Pemohon</h6>
            <div class="form-check mb-3">
                <input class="form-check-input {{ $errors->has('ack') ? 'is-invalid' : '' }}" type="checkbox" id="ack" name="ack" {{ old('ack')?'checked':'' }}>
                <label class="form-check-label" for="ack">
                    Saya mengesahkan maklumat adalah benar dan memahami proses kelulusan.
                </label>
                @if ($errors->has('ack'))
                    <div class="invalid-feedback d-block">@foreach ($errors->get('ack') as $e) {{ $e }} @endforeach</div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary"><i class="bx bx-send"></i> Hantar Permohonan</button>
            <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">Reset</button>
        </form>
    </div>
</div>
@endsection
