@extends('layouts.master')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Tempahan Ruang</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Senarai Tempahan</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('tempahan.form') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                <i class="bx bxs-plus-square"></i> Buat Tempahan
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h6 class="mb-0 text-uppercase">Senarai Tempahan</h6>
    <hr />

    <style>
        /* Pastikan modal content tidak transparent walaupun theme ada override */
        .modal-content {
            background: #fff !important;
        }
    </style>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Program</th>
                            <th>Kampus / Ruang</th>
                            <th>Tarikh & Masa</th>
                            <th>Perkhidmatan</th>
                            <th>Status</th>
                            <th>Surat</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tempahanList as $i => $t)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">
                                    {{ $t['nama_program'] ?? '-' }}<br>
                                    <small class="text-muted">{{ $t['tujuan'] ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>{{ $t['kampus_nama'] ?? '-' }}</div>
                                    <div class="text-muted">
                                        {{ ($t['ruang_nama'] ?? '-') . (isset($t['ruang_kod']) ? " ({$t['ruang_kod']})" : '') }}
                                    </div>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($t['tarikh_mula'])->format('d/m/Y h:i A') ?? '-' }}</div>
                                    <div class="text-muted">hingga</div>
                                    <div>{{ \Carbon\Carbon::parse($t['tarikh_tamat'])->format('d/m/Y h:i A') ?? '-' }}</div>
                                </td>
                                <td>
                                    @if (!empty($t['perkhidmatan']) && is_array($t['perkhidmatan']))
                                        <span
                                            class="badge bg-light text-dark">{{ implode(', ', $t['perkhidmatan']) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php $status = $t['status'] ?? 'Sedang Diproses'; @endphp
                                    @if (Str::startsWith($status, 'Disahkan'))
                                        <span class="badge bg-success">{{ $status }}</span>
                                    @elseif($status === 'Tidak Tersedia')
                                        <span class="badge bg-danger">{{ $status }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $status }}</span>
                                    @endif
                                    @if (!empty($t['pic_remark']))
                                        <div class="small text-muted mt-1">Catatan PIC: {{ $t['pic_remark'] }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($t['surat']))
                                        <span class="badge bg-info text-dark">Surat Diupload</span>
                                        <div class="small text-muted mt-1">{{ $t['surat']['nama'] }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tempahan.view', $t['id']) }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-show"></i> Papar
                                    </a>

                                    {{-- Aksi untuk status Sedang Diproses --}}
                                    @if (($t['status'] ?? '') === 'Sedang Diproses')
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal{{ $t['id'] }}">
                                            <i class="bx bx-check"></i> Sahkan
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $t['id'] }}">
                                            <i class="bx bx-x"></i> Tolak
                                        </button>
                                    @endif

                                    {{-- Aksi Upload Surat bila Disahkan (Available) & belum ada surat --}}
                                    @if (Str::startsWith($t['status'] ?? '', 'Disahkan') && empty($t['surat']))
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#uploadModal{{ $t['id'] }}">
                                            <i class="bx bx-upload"></i> Upload Surat
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal Sahkan (Bootstrap Proper) --}}
                            <div class="modal fade" id="confirmModal{{ $t['id'] }}" tabindex="-1" aria-hidden="true"
                                data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Sahkan Tempahan (Available)</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <form method="POST" action="{{ route('tempahan.pic.confirm', $t['id']) }}">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <label class="form-label">Catatan PIC (opsyenal)</label>
                                                <textarea name="remark" class="form-control" rows="2" placeholder="Contoh: Tiada pertindihan."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-success">Sahkan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Tolak (Bootstrap Proper) --}}
                            <div class="modal fade" id="rejectModal{{ $t['id'] }}" tabindex="-1" aria-hidden="true"
                                data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Tempahan (Tidak Tersedia)</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <form method="POST" action="{{ route('tempahan.pic.reject', $t['id']) }}">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <label class="form-label">Sebab / Catatan (disyorkan)</label>
                                                <textarea name="remark" class="form-control" rows="2" placeholder="Contoh: Bertembung dengan program lain."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Upload Surat (Bootstrap Proper) --}}
                            <div class="modal fade" id="uploadModal{{ $t['id'] }}" tabindex="-1"
                                aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Muat Naik Surat Permohonan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <form method="POST" action="{{ route('tempahan.upload.surat', $t['id']) }}"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Fail Surat (PDF/JPG/PNG)</label>
                                                    <input type="file" name="surat" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                                    <div class="form-text">Demo sahaja: fail tidak disimpan, hanya nama &
                                                        saiz direkod.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">Upload</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tiada tempahan.</td>
                            </tr>
                        @endforelse
                        {{-- 4. Menunggu Kelulusan Rektor -> Papar --}}
                        <tr>
                            <td>2</td>
                            <td class="fw-semibold">Program Keusahawanan<br><small class="text-muted">Program</small>
                            </td>
                            <td>
                                <div>UiTM Mukah</div>
                                <div class="text-muted">Auditorium (AUD-MUK)</div>
                            </td>
                            <td>
                                <div>25/11/2025 09:00 AM</div>
                                <div class="text-muted">hingga</div>
                                <div>25/11/2025 01:00 PM</div>
                            </td>
                            <td><span class="badge bg-light text-dark">WiFi</span></td>
                            <td><span class="badge bg-primary">Menunggu Kelulusan Rektor</span></td>
                            <td><span class="text-muted">-</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bx bx-show"></i> Papar</button>
                            </td>
                        </tr>

                        {{-- 5. Diluluskan -> Papar, Muat Turun Surat --}}
                        <tr>
                            <td>3</td>
                            <td class="fw-semibold">Hari Terbuka Fakulti<br><small class="text-muted">Pameran</small>
                            </td>
                            <td>
                                <div>UiTM Samarahan 1</div>
                                <div class="text-muted">Dewan Besar (DB-S1)</div>
                            </td>
                            <td>
                                <div>30/11/2025 08:00 AM</div>
                                <div class="text-muted">hingga</div>
                                <div>30/11/2025 05:00 PM</div>
                            </td>
                            <td><span class="badge bg-light text-dark">PA System</span></td>
                            <td><span class="badge bg-success">Diluluskan</span></td>
                            <td>
                                <span class="badge bg-info text-dark">Surat Kelulusan</span>
                                <div class="small text-muted mt-1">Kelulusan_DB_S1.pdf</div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bx bx-show"></i>
                                    Papar</button>
                                <button class="btn btn-sm btn-warning"><i class="bx bx-file"></i> Sebut Harga</button>
                            </td>
                        </tr>


                        {{-- 7. Tidak Tersedia -> Tiada tindakan --}}
                        <tr>
                            <td>4</td>
                            <td class="fw-semibold">Seminar Akademik<br><small class="text-muted">Seminar</small></td>
                            <td>
                                <div>UiTM Mukah</div>
                                <div class="text-muted">Auditorium (AUD-MUK)</div>
                            </td>
                            <td>
                                <div>08/12/2025 09:00 AM</div>
                                <div class="text-muted">hingga</div>
                                <div>08/12/2025 01:00 PM</div>
                            </td>
                            <td><span class="badge bg-light text-dark">WiFi, PA System</span></td>
                            <td><span class="badge bg-danger">Tidak Tersedia</span></td>
                            <td><span class="text-muted">-</span></td>
                            <td><span class="text-muted">Tiada tindakan</span></td>
                        </tr>
                        {{-- 8. Untuk Pembayaran (status: Disahkan, boleh upload bukti) --}}
                        <tr>
                            <td>5</td>
                            <td class="fw-semibold">Program Inovasi Pelajar<br><small class="text-muted">Pameran</small>
                            </td>
                            <td>
                                <div>UiTM Samarahan 2</div>
                                <div class="text-muted">Dewan Seminar (DS-S2)</div>
                            </td>
                            <td>
                                <div>10/12/2025 08:00 AM</div>
                                <div class="text-muted">hingga</div>
                                <div>10/12/2025 05:00 PM</div>
                            </td>
                            <td><span class="badge bg-light text-dark">WiFi, PA System</span></td>
                            <td><span class="badge bg-warning">Belum dibayar</span></td>
                            <td>
                                <span class="badge bg-info text-dark">Surat Diupload</span>
                                <div class="small text-muted mt-1">Kelulusan_DS_S2.pdf</div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bx bx-show"></i> Papar</button>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#payModal5">
                                    <i class="bx bx-money"></i> Bayar / Upload Bukti
                                </button>
                            </td>
                            {{-- Modal Bayar / Upload Bukti --}}
                            <div class="modal fade" id="payModal5" tabindex="-1" aria-hidden="true"
                                data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content bg-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Pembayaran bagi Seminar Akademik</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>

                                        {{-- NOTE: kalau route belum ada, tukar action="#" --}}
                                        <form method="POST" action="{{ route('tempahan.pembayaran.upload', 5) }}"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <div class="fw-semibold mb-1">Butiran Bayaran</div>
                                                    <div class="small text-muted">Sila buat bayaran menggunakan maklumat di
                                                        bawah, kemudian upload bukti pembayaran.</div>
                                                    <div class="border rounded p-3 mt-2">
                                                        <div><strong>Amaun Perlu Dibayar:</strong> RM 2,000.00</div>
                                                        <div><strong>Bank:</strong> RHB Bank Berhad</div>
                                                        <div><strong>No. Akaun:</strong> 1-23456-7890</div>
                                                        <div><strong>Rujukan dicadangkan:</strong>
                                                            SEMINAR-AUD-MUK-2025-12-10</div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Tarikh Bayaran</label>
                                                        <input type="date" name="tarikh_bayar" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Jumlah (RM)</label>
                                                        <input type="number" step="0.01" name="jumlah"
                                                            class="form-control" value="2000.00" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">No. Rujukan Transaksi (jika ada)</label>
                                                    <input type="text" name="rujukan" class="form-control"
                                                        placeholder="CTH: FPX123456 / TT-001">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Bukti Pembayaran (PDF/JPG/PNG)</label>
                                                    <input type="file" name="bukti" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                                    <div class="form-text">Mock-up: fail tidak disimpan, hanya notifikasi
                                                        berjaya.</div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Catatan (opsyenal)</label>
                                                    <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Bayaran FPX melalui Maybank2u."></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-success">Hantar Bukti</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
