@extends('layouts.master')
@section('content')

<style>
  /* === Vibe & spacing === */
  .dash-wrap { background:#f6fbff; border-radius:18px; padding:18px; }
  .kpi-card{ background:#fff; border-radius:22px; box-shadow:0 8px 24px rgba(0,0,0,.06); padding:20px; height:140px; }
  .kpi-title{ font-size:.9rem; color:#7a8aa0; margin-top:6px; }
  .kpi-value{ font-size:2rem; font-weight:700; color:#1e3a8a; line-height:1; }
  .kpi-btn{ margin-top:12px; font-size:.75rem; font-weight:600; border-radius:999px; padding:6px 12px; border:0; display:inline-block; text-decoration:none }
  .kpi-btn.green{ background:#35d16f; color:#fff; }
  .kpi-btn.orange{ background:#ff8f3a; color:#fff; }
  .kpi-btn.blue{ background:#4cc3ff; color:#fff; }
  .kpi-btn.red{ background:#ff5b5b; color:#fff; }

  .card-soft{ background:#fff; border-radius:22px; box-shadow:0 8px 24px rgba(0,0,0,.06); padding:18px; }
  .card-title-sm{ font-size:.95rem; font-weight:700; color:#2a3347; margin-bottom:10px; }
  .mini-legend { display:flex; gap:14px; align-items:center; }
  .mini-legend i{ display:inline-block; width:10px; height:10px; border-radius:999px; margin-right:6px; }
  .legend-blue{ background:#3b82f6; }
  .legend-grey{ background:#cfd8e3; }

  .soft-table thead th{ color:#8a97ad; font-weight:700; border:0; }
  .soft-table tbody td{ vertical-align:middle; }

  /* === IMPORTANT: fix chart height so it won't stretch === */
  .chart-box{ position: relative; height: 340px; width: 100%; }
  .chart-box-sm{ position: relative; height: 260px; width: 100%; }
</style>

<div class="container-fluid">

  {{-- ===== KPI ROW ===== --}}
  <div class="row g-3">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center">
        <div class="me-3"><i class="bx bx-group" style="font-size:32px;color:#7c8db5"></i></div>
        <div>
          <div class="kpi-value">45</div>
          <div class="kpi-title">Jumlah Tempahan</div>
          <a href="#" class="kpi-btn green">SENARAI TEMPAHAN</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center">
        <div class="me-3"><i class="bx bx-line-chart" style="font-size:32px;color:#7c8db5"></i></div>
        <div>
          <div class="kpi-value">RM10K</div>
          <div class="kpi-title">Jumlah Jana Sewa</div>
          <a href="#" class="kpi-btn orange">REKOD JANAA</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center">
        <div class="me-3"><i class="bx bx-time" style="font-size:32px;color:#7c8db5"></i></div>
        <div>
          <div class="kpi-value">5</div>
          <div class="kpi-title">Jumlah Sewaan Hari Ini</div>
          <a href="#" class="kpi-btn blue">SENARAI TEMPAHAN</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center">
        <div class="me-3"><i class="bx bx-transfer-alt" style="font-size:32px;color:#7c8db5"></i></div>
        <div>
          <div class="kpi-value">2</div>
          <div class="kpi-title">Menunggu Kelulusan</div>
          <a href="#" class="kpi-btn red">SENARAI TEMPAHAN</a>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== CHART ROW ===== --}}
  <div class="row g-3 mt-2">
    <div class="col-12 col-xl-8">
      <div class="dash-wrap card-soft">
        <div class="d-flex justify-content-between align-items-center">
          <div class="card-title-sm">JUMLAH TEMPAHAN DAN JANAA MENGIKUT BULAN</div>
          <div class="mini-legend">
            <span><i class="legend-blue"></i> Jumlah Tempahan</span>
            <span><i class="legend-grey"></i> Jumlah Jana Sewaan</span>
          </div>
        </div>

        {{-- fixed-height container for chart --}}
        <div class="chart-box">
          <canvas id="bookingRevenueChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-4">
      <div class="dash-wrap card-soft h-100">
        <div class="card-title-sm">KATEGORI RUANG DITEMPAH</div>
        <div class="chart-box-sm">
          <canvas id="roomCategoryChart"></canvas>
        </div>
        <div class="text-center mt-2">
          <div class="kpi-value" style="font-size:1.25rem">45</div>
          <div class="kpi-title" style="text-transform:uppercase">Jumlah Keseluruhan</div>
        </div>
        <div class="mt-2 mini-legend justify-content-center">
          <span><i class="legend-blue"></i> Dewan</span>
          <span><i class="legend-grey"></i> Audi</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== TABLE ROW ===== --}}
  <div class="row g-3 mt-2">
    <div class="col-12">
      <div class="dash-wrap card-soft">
        <div class="card-title-sm mb-2">SENARAI TEMPAHAN TERKINI</div>
        <div class="table-responsive">
          <table class="table soft-table">
            <thead>
              <tr>
                <th>RUANG</th>
                <th>TARIKH & MASA</th>
                <th>PEMOHON</th>
                <th>JENIS PEMOHON</th>
                <th class="text-end">JUMLAH KOS</th>
                <th class="text-center">STATUS TEMPAHAN</th>
                <th class="text-center">STATUS BAYARAN</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Dewan Jubli</td>
                <td>
                  18-10-2025 9.00AM<br>
                  <span class="text-muted">19-10-2025 5.00PM</span>
                </td>
                <td>Ali Bin Abu</td>
                <td>Warga UiTM</td>
                <td class="text-end">-</td>
                <td class="text-center"><span class="badge bg-success">Lulus</span></td>
                <td class="text-center"><span class="badge bg-secondary">Belum</span></td>
              </tr>
              <tr>
                <td>Auditorium</td>
                <td>
                  25-11-2025 9.00AM<br>
                  <span class="text-muted">25-11-2025 1.00PM</span>
                </td>
                <td>Maryam Musa</td>
                <td>Warga UiTM</td>
                <td class="text-end">RM 2,000.00</td>
                <td class="text-center"><span class="badge bg-primary">Menunggu Rektor</span></td>
                <td class="text-center"><span class="badge bg-warning text-dark">Pending</span></td>
              </tr>
              <tr>
                <td>Dewan Besar</td>
                <td>
                  30-11-2025 8.00AM<br>
                  <span class="text-muted">30-11-2025 5.00PM</span>
                </td>
                <td>Roslan Kassim</td>
                <td>Orang Awam</td>
                <td class="text-end">RM 1,500.00</td>
                <td class="text-center"><span class="badge bg-success">Lulus</span></td>
                <td class="text-center"><span class="badge bg-success">Selesai</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
  // ==== BAR CHART ====
  (function(){
    var ctx = document.getElementById('bookingRevenueChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Oct','Nov','Dec','Jan','Feb','Mar'],
        datasets: [
          { label:'Jumlah Tempahan', data:[3,5,7,2,4,6], backgroundColor:'#3b82f6' },
          { label:'Jumlah Jana Sewaan', data:[1,3,2,1,2,3], backgroundColor:'#cfd8e3' }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false, // follow .chart-box height
        plugins: { legend: { display:false } },
        scales: {
          x: { grid: { display:false } },
          y: {
            beginAtZero:true,
            grid: { color:'rgba(0,0,0,.05)' },
            ticks: { callback: function(v){ return 'RM'+(v*500); } }
          }
        }
      }
    });
  })();

  // ==== DOUGHNUT CHART ====
  (function(){
    var ctx2 = document.getElementById('roomCategoryChart').getContext('2d');
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['Dewan','Audi'],
        datasets: [{
          data: [34,11],
          backgroundColor: ['#3b82f6','#cfd8e3'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false, // follow .chart-box-sm height
        plugins: { legend: { display:false } },
        cutout: '72%'
      }
    });
  })();
</script>
@endsection
