@extends('admin.layout')
@section('title', 'Analytics & Reports')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="row g-3 mb-4">
    <!-- Sentiment Chart -->
    <div class="col-md-6">
        <div class="stat-card">
            <h6 style="font-weight:700;color:#1A1A2E;margin-bottom:16px;">
                <i class="bi bi-pie-chart me-2 text-primary"></i>Sentiment Distribution
            </h6>
            <canvas id="sentimentChart" height="200"></canvas>
        </div>
    </div>

    <!-- Status Chart -->
    <div class="col-md-6">
        <div class="stat-card">
            <h6 style="font-weight:700;color:#1A1A2E;margin-bottom:16px;">
                <i class="bi bi-bar-chart me-2 text-primary"></i>Ticket Status Overview
            </h6>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="icon mx-auto mb-2" style="background:#FFEBEE;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-exclamation-triangle" style="color:#C62828;font-size:22px;"></i>
            </div>
            <div style="font-size:28px;font-weight:700;color:#C62828;">{{ $sentimentData['Urgent'] }}</div>
            <div style="font-size:13px;color:#666;">Urgent Tickets</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="icon mx-auto mb-2" style="background:#FFF8E1;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-emoji-frown" style="color:#F57F17;font-size:22px;"></i>
            </div>
            <div style="font-size:28px;font-weight:700;color:#F57F17;">{{ $sentimentData['Frustrated'] }}</div>
            <div style="font-size:13px;color:#666;">Frustrated Tickets</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="icon mx-auto mb-2" style="background:#E3F2FD;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-emoji-smile" style="color:#1565C0;font-size:22px;"></i>
            </div>
            <div style="font-size:28px;font-weight:700;color:#1565C0;">{{ $sentimentData['Neutral'] }}</div>
            <div style="font-size:13px;color:#666;">Neutral Tickets</div>
        </div>
    </div>
</div>

<!-- Monthly Chart -->
<div class="stat-card">
    <h6 style="font-weight:700;color:#1A1A2E;margin-bottom:16px;">
        <i class="bi bi-graph-up me-2 text-primary"></i>Monthly Ticket Volume ({{ date('Y') }})
    </h6>
    <canvas id="monthlyChart" height="100"></canvas>
</div>

@push('scripts')
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const monthlyData = @json($monthlyTickets);
const monthlyLabels = monthlyData.map(d => months[d.month - 1]);
const monthlyCounts = monthlyData.map(d => d.count);

// Sentiment Pie Chart
new Chart(document.getElementById('sentimentChart'), {
    type: 'doughnut',
    data: {
        labels: ['Urgent', 'Frustrated', 'Neutral'],
        datasets: [{
            data: [{{ $sentimentData['Urgent'] }}, {{ $sentimentData['Frustrated'] }}, {{ $sentimentData['Neutral'] }}],
            backgroundColor: ['#EF5350', '#FFA726', '#42A5F5'],
            borderWidth: 0,
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } },
        cutout: '65%',
    }
});

// Status Bar Chart
new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: ['Pending', 'Processing', 'Resolved'],
        datasets: [{
            label: 'Tickets',
            data: [{{ $statusData['Pending'] }}, {{ $statusData['Processing'] }}, {{ $statusData['Resolved'] }}],
            backgroundColor: ['#42A5F5', '#FFA726', '#66BB6A'],
            borderRadius: 8,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Monthly Line Chart
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: monthlyLabels.length ? monthlyLabels : months,
        datasets: [{
            label: 'Tickets',
            data: monthlyCounts.length ? monthlyCounts : [0,0,0,0,0,0,0,0,0,0,0,0],
            borderColor: '#1565C0',
            backgroundColor: 'rgba(21,101,192,0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#1565C0',
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush
@endsection