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
                <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Sebut Harga</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">{{ $str_mode }} Sebut Harga</h6>
<hr />

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ $save_route }}" onsubmit="return true;">
            {{ csrf_field() }}

            {{-- Pilih tempahan --}}
            <div class="mb-3">
                <label class="form-label">Tempahan Berkaitan</label>
                <select name="tempahan_id" class="form-select" required>
                    <option value="">-- Pilih Tempahan --</option>
                    @foreach($tempahanList as $tid => $t)
                        <option value="{{ $tid }}"
                            @if( old('tempahan_id', $sebutharga['tempahan_id'] ?? '') == $tid) selected @endif>
                            [#{{ $t['id'] }}] {{ $t['nama_program'] }} — {{ $t['kampus_nama'] }} ({{ \Carbon\Carbon::parse($t['tarikh_mula'])->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($t['tarikh_tamat'])->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Rujukan</label>
                    <input type="text" name="no_rujukan" class="form-control"
                           value="{{ old('no_rujukan', $sebutharga['no_rujukan'] ?? '') }}" placeholder="CTH: UITM/SBH/2025/001">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Tajuk</label>
                    <input type="text" name="tajuk" class="form-control"
                           value="{{ old('tajuk', $sebutharga['tajuk'] ?? 'Fasiliti & Prasarana') }}">
                </div>
            </div>

            <hr class="my-4">

            <h6 class="fw-bold mb-2">Item (Fasiliti & Prasarana)</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:55%">FASILITI & PRASARANA</th>
                            <th style="width:25%">KADAR SEWA (RM)</th>
                            <th style="width:15%">JUMLAH (RM)</th>
                            <th style="width:5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rows = old('items', $sebutharga['items'] ?? [
                                ['desc' => 'Dewan Jubli (1–5 Mei 2025)', 'rate' => '2,500.00 / hari x 5', 'amount' => 12500.00],
                                ['desc' => 'Kerusi banquet', 'rate' => '4 x 100', 'amount' => 400.00],
                            ]);
                        @endphp

                        @foreach($rows as $idx => $row)
                        <tr>
                            <td>
                                <input type="text" name="items[{{ $idx }}][desc]" class="form-control"
                                       value="{{ $row['desc'] ?? '' }}" placeholder="Contoh: Dewan / Kerusi / Bilik">
                            </td>
                            <td>
                                <input type="text" name="items[{{ $idx }}][rate]" class="form-control"
                                       value="{{ $row['rate'] ?? '' }}" placeholder="Contoh: 2,500.00 / hari x 5">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $idx }}][amount]" class="form-control amount-field"
                                       value="{{ $row['amount'] ?? '' }}" placeholder="0.00">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="bx bx-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Jumlah Keseluruhan (RM)</td>
                            <td><input type="text" class="form-control fw-bold text-end" id="grandTotal" readonly></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="button" class="btn btn-outline-secondary" onclick="addRow()">
                <i class="bx bx-plus"></i> Tambah Item
            </button>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
                <a href="{{ route('sebutharga.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

{{-- Simple repeater + auto total --}}
<script>
    function addRow() {
        const tbody = document.querySelector('#itemsTable tbody');
        const idx = tbody.rows.length;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="items[${idx}][desc]" class="form-control" placeholder="Contoh: Dewan / Kerusi / Bilik"></td>
            <td><input type="text" name="items[${idx}][rate]" class="form-control" placeholder="Contoh: 2,500.00 / hari x 5"></td>
            <td><input type="number" step="0.01" name="items[${idx}][amount]" class="form-control amount-field" placeholder="0.00"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="bx bx-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
        bindAmountInputs();
    }
    function removeRow(btn) {
        btn.closest('tr').remove();
        calcTotal();
    }
    function bindAmountInputs() {
        document.querySelectorAll('.amount-field').forEach(inp => {
            inp.removeEventListener('input', calcTotal);
            inp.addEventListener('input', calcTotal);
        });
    }
    function calcTotal() {
        let sum = 0;
        document.querySelectorAll('.amount-field').forEach(inp => {
            const v = parseFloat(inp.value);
            if (!isNaN(v)) sum += v;
        });
        document.getElementById('grandTotal').value = sum.toFixed(2);
    }
    document.addEventListener('DOMContentLoaded', () => { bindAmountInputs(); calcTotal(); });
</script>
@endsection
