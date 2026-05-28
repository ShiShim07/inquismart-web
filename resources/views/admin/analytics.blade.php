@extends('admin.layout')
@section('title', 'Service Analytics')

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
                    <i class="bi bi-emoji-frown" style="color:#DC2626;"></i>
                </div>
                <div>
                    <div class="stat-label">Negative Tickets</div>
                    <div class="stat-value" style="color:#991B1B;font-size:26px;">{{ $sentimentData['Negative'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F0FDF4;flex-shrink:0;">
                    <i class="bi bi-emoji-smile" style="color:#22C55E;"></i>
                </div>
                <div>
                    <div class="stat-label">Positive Tickets</div>
                    <div class="stat-value" style="color:#166534;font-size:26px;">{{ $sentimentData['Positive'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EFF6FF;flex-shrink:0;">
                    <i class="bi bi-emoji-neutral" style="color:#3B82F6;"></i>
                </div>
                <div>
                    <div class="stat-label">Neutral Tickets</div>
                    <div class="stat-value" style="color:#1E40AF;font-size:26px;">{{ $sentimentData['Neutral'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Satisfaction Rate --}}
@if(isset($satisfactionRate))
<div class="card-surface mb-4">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h6 class="section-header"><i class="bi bi-star"></i> Customer Satisfaction Rate</h6>
            <p style="font-size:13px;color:var(--text-muted);margin:4px 0 0;">Based on Positive vs Negative sentiment ratio</p>
        </div>
        <div style="text-align:right;">
            <div style="font-size:36px;font-weight:700;color:{{ $satisfactionRate >= 70 ? '#166534' : ($satisfactionRate >= 40 ? '#92400E' : '#991B1B') }};">
                {{ $satisfactionRate }}%
            </div>
            <div style="font-size:12px;color:var(--text-muted);">
                @if($satisfactionRate >= 70) 😊 Good
                @elseif($satisfactionRate >= 40) 😐 Average
                @else 😟 Needs improvement
                @endif
            </div>
        </div>
    </div>
</div>
@endif

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
<div class="card-surface mb-4">
    <h6 class="section-header mb-4">
        <i class="bi bi-graph-up"></i> Monthly Ticket Volume — {{ date('Y') }}
    </h6>
    <canvas id="monthlyChart" height="90"></canvas>
</div>

{{-- Top Inquiry Categories (M14 Predictive Insight) --}}
@if(isset($topCategories) && count($topCategories))
<div class="card-surface mb-4">
    <h6 class="section-header mb-4">
        <i class="bi bi-lightbulb"></i> Top Inquiry Categories
        <span style="font-size:11px;color:var(--text-muted);font-weight:400;margin-left:8px;">Predictive Inquiry Insight</span>
    </h6>
    <div class="row g-3">
        @foreach(array_slice($topCategories, 0, 6, true) as $category => $count)
        <div class="col-md-4">
            <div style="background:#F8FAFC;border-radius:10px;padding:14px 16px;border:1px solid #E5E7EB;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:13px;font-weight:600;color:#1A1A2E;">{{ $category }}</span>
                <span style="background:#EFF6FF;color:#1E40AF;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">{{ $count }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@push('scripts')
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const monthlyData = @json($monthlyTickets);
const monthlyLabels = monthlyData.map(d => months[d.month - 1]);
const monthlyCounts = monthlyData.map(d => d.count);

Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
Chart.defaults.color = '#64748B';

// Sentiment Chart — Updated: Negative/Positive/Neutral
new Chart(document.getElementById('sentimentChart'), {
    type: 'doughnut',
    data: {
        labels: ['Negative', 'Positive', 'Neutral'],
        datasets: [{
            data: [
                {{ $sentimentData['Negative'] ?? 0 }},
                {{ $sentimentData['Positive'] ?? 0 }},
                {{ $sentimentData['Neutral'] ?? 0 }}
            ],
            backgroundColor: ['#EF4444', '#22C55E', '#3B82F6'],
            borderWidth: 0,
            hoverOffset: 4,
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 16, font: { size: 13 }, boxWidth: 12, boxHeight: 12 }
            }
        },
        cutout: '68%',
    }
});

// Status Chart
new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: ['Pending', 'Processing', 'Resolved'],
        datasets: [{
            label: 'Tickets',
            data: [
                {{ $statusData['Pending'] ?? 0 }},
                {{ $statusData['Processing'] ?? 0 }},
                {{ $statusData['Resolved'] ?? 0 }}
            ],
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

// Monthly Volume Chart
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