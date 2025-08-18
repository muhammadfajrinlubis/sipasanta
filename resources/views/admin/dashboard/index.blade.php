@extends('admin.layouts.app', ['activePage' => 'dashboard'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="widget p-md clearfix">
            <h1 class="widget-title" style="font-size: 24px; margin-bottom: 10px;">Dashboard</h1>
            <small class="text-color">Home <span> &gt; </span> <a href="/admin/home">Dashboard</a></small>
        </div>
    </div>
</div>

<!-- Filter Tahun -->
<form method="GET" action="/admin/home">
    <div class="row mb-3">
        <div class="col-md-4 col-sm-6">
            <select name="year" class="form-control" onchange="this.form.submit()">
                <option value="" {{ is_null($selectedYear) ? 'selected' : '' }}>All Years</option>
                @foreach($availableYears as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

@if (in_array(Auth::user()->level, [1, 2]))
<div class="row">
    <!-- Total Pengaduan -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="widget stats-widget">
            <div class="widget-body d-flex align-items-center">
                <div>
                    <h3 class="widget-title text-primary">{{ $total_pengaduan }} Pengadu</h3>
                    <small>Total Pengaduan</small>
                </div>
                <i class="fa zmdi zmdi-file-text zmdi-hc-lg ml-auto text-primary"></i>
            </div>
        </div>
    </div>
    <!-- Total Laundry -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="widget stats-widget">
            <div class="widget-body d-flex align-items-center">
                <div>
                    <h3 class="widget-title text-danger">{{ $laundry }} Laundry</h3>
                    <small>Total Pengajuan Laundry</small>
                </div>
                <i class="fa zmdi zmdi-washing-machine zmdi-hc-lg ml-auto text-danger"></i>
            </div>
        </div>
    </div>
    <!-- Rekap Petugas -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="widget stats-widget">
            <div class="widget-body d-flex align-items-center">
                <div>

                   <h3 class="widget-title text-info">{{ $total_pengaduan_selesai }} Pengaduan Selesai</h3>
                    <small>Total Pengaduan Selesai</small>

                </div>
                <i class="fa zmdi zmdi-assignment-check zmdi-hc-lg ml-auto text-info"></i>
            </div>
        </div>
    </div>
    <!-- Total Pengaduan Ditolak -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="widget stats-widget">
            <div class="widget-body d-flex align-items-center">
                <div>
                    <h3 class="widget-title text-danger">{{ $total_pengaduan_ditolak }} Pengaduan Ditolak</h3>
                    <small>Status Ditolak</small>
                </div>
                <i class="fa zmdi zmdi-close zmdi-hc-lg ml-auto text-danger"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="widget p-md clearfix">
            <h4>Rekap Petugas - Pengaduan Selesai dan Sedang Dikerjakan</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Petugas</th>
                        <th>Total Selesai</th>
                        <th>Sedang Dikerjakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap_petugas as $rekap)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rekap->nama_petugas }}</td>
                            <td>{{ $rekap->total_selesai }}</td>
                            <td>{{ $rekap->total_sedang }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-6">

        <!-- Grafik Pengaduan Bulanan -->
        <div class="widget p-md clearfix">
            <div class="widget-body d-flex align-items-center">
                <div class="card-header">
                    <h4>Grafik Pengaduan Bulanan (Tahun {{ $selectedYear ?? 'All Years' }})</h4>
                </div>
                <div class="card-body">
                    <canvas id="pengaduanBulananChart" style="height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Diagram Pai -->
<div class="row">
    <div class="col-md-6">
        <div class="widget p-md clearfix">
            <div class="widget-body d-flex align-items-center">
                <div class="card-header">
                    <h4>Diagram Pai - Status Pengaduan</h4>
                </div>
                <div class="card-body">
                    <canvas id="statusPengaduanChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="widget p-md clearfix">
            <div class="widget-body d-flex align-items-center">
                <div class="card-header">
                    <h4>Grafik Pengaduan Bulanan (Tahun {{ $selectedYear ?? 'All Years' }})</h4>
                </div>
                <div class="card-body">
                    <div id="pengaduanChart" style="height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script>
    // Data untuk Grafik Pengaduan Bulanan
    const pengaduanPerBulan = @json($pengaduan_per_bulan);
    const ctxPengaduan = document.getElementById('pengaduanBulananChart').getContext('2d');
    new Chart(ctxPengaduan, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Jumlah Pengaduan',
                data: Object.values(pengaduanPerBulan),
                backgroundColor: '#4BC0C0',
                borderColor: '#4BC0C0',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Diagram Pai - Status Pengaduan
    const pengaduanByStatus = @json($pengaduan_by_status);
    const ctx = document.getElementById('statusPengaduanChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(pengaduanByStatus),
            datasets: [{
                data: Object.values(pengaduanByStatus),
                backgroundColor: ['#FF6384', '#FF9F40', '#4BC0C0', '#C9CBCF'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Grafik Pengaduan Bulanan - Echarts
    const pengaduanData = @json($pengaduan_per_bulan);
    const pengaduanChart = echarts.init(document.getElementById('pengaduanChart'));
    pengaduanChart.setOption({
        tooltip: { trigger: 'axis' },
        xAxis: { type: 'category', data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] },
        yAxis: { type: 'value' },
        series: [{
            data: Array.from({ length: 12 }, (_, i) => pengaduanData[i + 1] || 0),
            type: 'line',
            smooth: true,
            areaStyle: {}
        }]
    });
</script>

@elseif (in_array(Auth::user()->level, [5]))
<div class="row">
    <!-- Total Laundry -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="widget stats-widget">
            <div class="widget-body d-flex align-items-center">
                <div>
                    <h3 class="widget-title text-danger">{{ $totalLaundrypetugas }} Laundry</h3>
                    <small>Total Pengajuan Laundry</small>
                </div>
                <i class="fa zmdi zmdi-washing-machine zmdi-hc-lg ml-auto text-danger"></i>
            </div>
        </div>
    </div>

  <div class="col-md-3 col-sm-6 mb-3">
    <div class="widget stats-widget">
        <div class="widget-body d-flex align-items-center">
            <div>
                <h3 class="widget-title text-success">
                    Rp {{ number_format($totalPendapatan, 3, ',', '.', ) }}
                </h3>
                <small>Total Pendapatan Laundry</small>
            </div>
            <i class="fa zmdi zmdi-money zmdi-hc-lg ml-auto text-success"></i>
        </div>
    </div>
</div>
@endif
@endsection
