@extends('admin.layout')
@section('title', 'Analytics & Reports')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

{{-- Summary Cards Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FEF2F2;flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle" style="color:#DC2626;"></i>
                </div>
                <div>
                    <div class="stat-label">Urgent Tickets</div>
                    <div class="stat-value" style="color:#991B1B;font-size:26px;">{{ $sentimentData['Urgent'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFFBEB;flex-shrink:0;">
                    <i class="bi bi-emoji-frown" style="color:#D97706;"></i>
                </div>
                <div>
                    <div class="stat-label">Frustrated Tickets</div>
                    <div class="stat-value" style="color:#92400E;font-size:26px;">{{ $sentimentData['Frustrated'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EFF6FF;flex-shrink:0;">
                    <i class="bi bi-emoji-smile" style="color:#3B82F6;"></i>
                </div>
                <div>
                    <div class="stat-label">Neutral Tickets</div>
                    <div class="stat-value" style="color:#1E40AF;font-size:26px;">{{ $sentimentData['Neutral'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="card-surface h-100">
            <h6 class="section-header mb-4">
                <i class="bi bi-pie-chart"></i> Sentiment Distribution
            </h6>
            <div style="position:relative;max-width:280px;margin:0 auto;">
                <canvas id="sentimentChart" height="240"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card-surface h-100">
            <h6 class="section-header mb-4">
                <i class="bi bi-bar-chart"></i> Ticket Status Overview
            </h6>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Monthly Chart --}}
<div class="card-surface">
    <h6 class="section-header mb-4">
        <i class="bi bi-graph-up"></i> Monthly Ticket Volume — {{ date('Y') }}
    </h6>
    <canvas id="monthlyChart" height="90"></canvas>
</div>

@push('scripts')
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const monthlyData = @json($monthlyTickets);
const monthlyLabels = monthlyData.map(d => months[d.month - 1]);
const monthlyCounts = monthlyData.map(d => d.count);

Chart.defaults.font.family = "'DM Sans', system-ui, sans-serif";
Chart.defaults.color = '#64748B';

new Chart(document.getElementById('sentimentChart'), {
    type: 'doughnut',
    data: {
        labels: ['Urgent', 'Frustrated', 'Neutral'],
        datasets: [{
            data: [{{ $sentimentData['Urgent'] }}, {{ $sentimentData['Frustrated'] }}, {{ $sentimentData['Neutral'] }}],
            backgroundColor: ['#EF4444', '#F59E0B', '#3B82F6'],
            borderWidth: 0,
            hoverOffset: 4,
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 16, font: { size: 13 }, boxWidth: 12, boxHeight: 12, borderRadius: 3 }
            }
        },
        cutout: '68%',
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: ['Pending', 'Processing', 'Resolved'],
        datasets: [{
            label: 'Tickets',
            data: [{{ $statusData['Pending'] }}, {{ $statusData['Processing'] }}, {{ $statusData['Resolved'] }}],
            backgroundColor: ['rgba(59,130,246,0.15)', 'rgba(245,158,11,0.15)', 'rgba(34,197,94,0.15)'],
            borderColor: ['#3B82F6', '#F59E0B', '#22C55E'],
            borderWidth: 1.5,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { size: 12 } },
                grid: { color: 'rgba(0,0,0,0.04)' },
                border: { color: 'transparent' }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 13 } },
                border: { color: 'transparent' }
            }
        },
    }
});

new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: monthlyLabels.length ? monthlyLabels : months,
        datasets: [{
            label: 'Tickets',
            data: monthlyCounts.length ? monthlyCounts : new Array(12).fill(0),
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59,130,246,0.06)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { size: 12 } },
                grid: { color: 'rgba(0,0,0,0.04)' },
                border: { color: 'transparent' }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 12 } },
                border: { color: 'transparent' }
            }
        }
    }
});
</script>
@endpush

@endsection
