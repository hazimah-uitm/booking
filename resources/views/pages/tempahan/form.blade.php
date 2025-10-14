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
        <li class="breadcrumb-item active" aria-current="page">Borang Tempahan</li>
      </ol>
    </nav>
  </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Sewa Ruang</h6>
<hr/>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('tempahan.submit') }}" id="frmTempahan">
      {{ csrf_field() }}

      {{-- ========== 1) Kampus & Ruang (atas) ========== --}}
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="mb-0 text-uppercase">Kampus & Ruang</h6>
        <small class="text-muted">Pilih kampus dahulu, kemudian pilih ruang</small>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Pilih Kampus</label>
          <select class="form-select" id="kampusSelect" name="kampus_id" required>
            <option value="" disabled {{ old('kampus_id') ? '' : 'selected' }}>-- Pilih Kampus --</option>
            @foreach($campusOptions as $c)
              <option value="{{ $c['id'] }}" data-name="{{ $c['name'] }}" {{ old('kampus_id') === $c['id'] ? 'selected' : '' }}>
                {{ $c['name'] }}
              </option>
            @endforeach
          </select>
          <input type="hidden" name="kampus_nama" id="kampusNama" value="{{ old('kampus_nama') }}">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Pilih Ruang</label>
          <select class="form-select" id="ruangSelect" name="ruang_kod" required {{ old('kampus_id') ? '' : 'disabled' }}>
            <option value="">{{ old('kampus_id') ? '-- Pilih Ruang --' : '-- Sila pilih kampus dahulu --' }}</option>
          </select>
          <input type="hidden" name="ruang_nama" id="ruangNama" value="{{ old('ruang_nama') }}">
        </div>
      </div>

      <div id="ruangCards" class="row g-3 mt-2">
        <!-- Diisi oleh JS sebagai galeri kad ruang -->
      </div>

      {{-- ========== 2) Perkhidmatan Tambahan (terus bawah kampus & ruang) ========== --}}
      <hr class="my-4"/>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-semibold">Perkhidmatan Tambahan</label>
          <div class="d-flex flex-wrap gap-3">
            @php $svcOld = (array) old('perkhidmatan', []); @endphp
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="perkhidmatan[]" value="Perhiasan" id="chkPerhiasan"
                     {{ in_array('Perhiasan', $svcOld) ? 'checked' : '' }}>
              <label class="form-check-label" for="chkPerhiasan">Perhiasan</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="perkhidmatan[]" value="WiFi" id="chkWifi"
                     {{ in_array('WiFi', $svcOld) ? 'checked' : '' }}>
              <label class="form-check-label" for="chkWifi">WiFi</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="perkhidmatan[]" value="Audio Visual" id="chkAV"
                     {{ in_array('Audio Visual', $svcOld) ? 'checked' : '' }}>
              <label class="form-check-label" for="chkAV">Audio Visual</label>
            </div>
          </div>
        </div>
      </div>

      {{-- ========== 3) Nama Program (full width) ========== --}}
      <hr class="my-4"/>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-semibold">Nama Program</label>
          <input type="text" class="form-control" name="nama_program"
                 placeholder="Contoh: Seminar Inovasi 2025"
                 value="{{ old('nama_program') }}" required>
        </div>
      </div>

      {{-- ========== 4) Tarikh & Masa (1 baris 2 kolum) ========== --}}
      <div class="row g-3 mt-0">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tarikh & Masa Mula</label>
          <input type="datetime-local" class="form-control" name="tarikh_mula" value="{{ old('tarikh_mula') }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tarikh & Masa Tamat</label>
          <input type="datetime-local" class="form-control" name="tarikh_tamat" value="{{ old('tarikh_tamat') }}" required>
        </div>
      </div>

      {{-- ========== 5) Tujuan / Penerangan (textarea) ========== --}}
      <div class="row g-3 mt-1">
        <div class="col-12">
          <label class="form-label fw-semibold d-flex align-items-center gap-2">
            Tujuan / Penerangan Program
            <small class="text-muted">(boleh letak perenggan atau senarai ringkas)</small>
          </label>
          <textarea class="form-control" name="tujuan" rows="4" maxlength="1000"
                    placeholder="Contoh: Taklimat keselamatan, sesi soal jawab, susun atur pentas, keperluan teknikal, dll."
                    oninput="updateCounter(this)">{{ old('tujuan') }}</textarea>
          <div class="d-flex justify-content-between">
            <small class="text-muted">Beritahu keperluan khusus (layout, PA system, dsb.).</small>
            <small class="text-muted"><span id="tujuanCount">0</span>/1000</small>
          </div>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Hantar Permohonan</button>
        <a href="{{ route('tempahan.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>

{{-- ========== JS Demo Data + UI Binding ========== --}}
<script>
const DATA_RUANG = {
  "S1": [
    { kod:"DJ-S1",  nama:"Dewan Jubli",   kapasiti:800,  img:"https://images.unsplash.com/photo-1503428593586-e225b39bddfe?q=80&w=1200&auto=format&fit=crop" },
    { kod:"AUD-S1", nama:"Auditorium",    kapasiti:300,  img:"https://images.unsplash.com/photo-1461360370896-922624d12aa1?q=80&w=1200&auto=format&fit=crop" },
    { kod:"BK1-S1", nama:"Bilik Kuliah 1",kapasiti:60,   img:"https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?q=80&w=1200&auto=format&fit=crop" },
  ],
  "S2": [
    { kod:"DJ-S2",  nama:"Dewan Jubli",   kapasiti:1000, img:"https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop" },
    { kod:"AUD-S2", nama:"Auditorium",    kapasiti:350,  img:"https://images.unsplash.com/photo-1470229702981-0f158b006d51?q=80&w=1200&auto=format&fit=crop" },
    { kod:"BK1-S2", nama:"Bilik Kuliah 1",kapasiti:80,   img:"https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1200&auto=format&fit=crop" },
  ],
  "MUK": [
    { kod:"AUD-MUK",  nama:"Auditorium",   kapasiti:250, img:"https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=1200&auto=format&fit=crop" },
    { kod:"BK-A-MUK", nama:"Bilik Kuliah A",kapasiti:50, img:"https://images.unsplash.com/photo-1519452575417-564c1401ecc0?q=80&w=1200&auto=format&fit=crop" },
  ],
};

const kampusSelect = document.getElementById('kampusSelect');
const ruangSelect  = document.getElementById('ruangSelect');
const ruangCards   = document.getElementById('ruangCards');
const inputKampusNama = document.getElementById('kampusNama');
const inputRuangNama  = document.getElementById('ruangNama');

function populateRuang(kampusId) {
  const list = DATA_RUANG[kampusId] || [];

  // Dropdown ruang
  ruangSelect.innerHTML = list.length
    ? '<option value="" disabled selected>-- Pilih Ruang --</option>'
    : '<option value="">-- Tiada ruang untuk kampus ini --</option>';
  list.forEach(r => {
    const opt = document.createElement('option');
    opt.value = r.kod;
    opt.textContent = `${r.nama} (Kapasiti ${r.kapasiti})`;
    opt.dataset.nama = r.nama;
    ruangSelect.appendChild(opt);
  });
  ruangSelect.disabled = list.length === 0;

  // Kad ruang
  ruangCards.innerHTML = '';
  list.forEach(r => {
    const col = document.createElement('div');
    col.className = 'col-md-4';
    col.innerHTML = `
      <div class="card h-100 ruang-card" data-kod="${r.kod}" data-nama="${r.nama}">
        <img src="${r.img}" class="card-img-top" alt="${r.nama}" style="object-fit:cover;height:180px;">
        <div class="card-body">
          <h6 class="card-title mb-1">${r.nama}</h6>
          <p class="text-muted mb-0">Kapasiti: ${r.kapasiti} orang</p>
        </div>
      </div>`;
    ruangCards.appendChild(col);
  });
}

kampusSelect?.addEventListener('change', function() {
  const kampusId = this.value;
  const kampusNama = this.options[this.selectedIndex].dataset.name || '';
  inputKampusNama.value = kampusNama;

  populateRuang(kampusId);

  // Reset ruang
  ruangSelect.value = '';
  inputRuangNama.value = '';
  setSelectedCard(null);
});

ruangCards?.addEventListener('click', function(e) {
  const card = e.target.closest('.ruang-card');
  if (!card) return;
  const kod = card.dataset.kod;
  const nama = card.dataset.nama;

  ruangSelect.value = kod;
  inputRuangNama.value = nama;
  setSelectedCard(card);
});

ruangSelect?.addEventListener('change', function(){
  const kod = this.value;
  const option = this.options[this.selectedIndex];
  inputRuangNama.value = option?.dataset?.nama || '';
  const card = [...document.querySelectorAll('.ruang-card')].find(c => c.dataset.kod === kod) || null;
  setSelectedCard(card);
});

function setSelectedCard(activeCard){
  document.querySelectorAll('.ruang-card').forEach(c => c.classList.remove('border','border-primary','shadow'));
  if (activeCard){
    activeCard.classList.add('border','border-primary','shadow');
  }
}

function updateCounter(textarea){
  const el = document.getElementById('tujuanCount');
  if (el) el.textContent = textarea.value.length;
}

document.addEventListener('DOMContentLoaded', function(){
  // Prefill kaunter tujuan
  const tujuan = document.querySelector('textarea[name="tujuan"]');
  if (tujuan) updateCounter(tujuan);

  // Prefill ruang bila old()
  const oldKampus = "{{ old('kampus_id') }}";
  const oldRuang  = "{{ old('ruang_kod') }}";
  const oldKampusNama = "{{ old('kampus_nama') }}";
  const oldRuangNama  = "{{ old('ruang_nama') }}";

  if (oldKampus){
    populateRuang(oldKampus);
    inputKampusNama.value = oldKampusNama || (kampusSelect.selectedOptions[0]?.dataset?.name || '');
    if (oldRuang){
      ruangSelect.value = oldRuang;
      const opt = [...ruangSelect.options].find(o => o.value === oldRuang);
      inputRuangNama.value = oldRuangNama || (opt?.dataset?.nama || '');
      const card = [...document.querySelectorAll('.ruang-card')].find(c => c.dataset.kod === oldRuang) || null;
      setSelectedCard(card);
    }
    ruangSelect.disabled = false;
  }
});
</script>
@endsection
