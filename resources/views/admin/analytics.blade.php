@extends('admin.layout')
@section('title', 'Service Analytics')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card fade-up delay-1">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFF0F2;flex-shrink:0;">
                    <i class="bi bi-emoji-frown-fill" style="color:var(--danger);"></i>
                </div>
                <div>
                    <div class="stat-label">Negative Tickets</div>
                    <div class="stat-value" style="color:var(--danger);font-size:26px;">{{ $sentimentData['Negative'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card fade-up delay-2">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EDFFF9;flex-shrink:0;">
                    <i class="bi bi-emoji-smile-fill" style="color:var(--success);"></i>
                </div>
                <div>
                    <div class="stat-label">Positive Tickets</div>
                    <div class="stat-value" style="color:var(--success);font-size:26px;">{{ $sentimentData['Positive'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card fade-up delay-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EEF2FF;flex-shrink:0;">
                    <i class="bi bi-emoji-neutral-fill" style="color:var(--primary);"></i>
                </div>
                <div>
                    <div class="stat-label">Neutral Tickets</div>
                    <div class="stat-value" style="color:var(--primary);font-size:26px;">{{ $sentimentData['Neutral'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Satisfaction Rate --}}
@if(isset($satisfactionRate))
<div class="surface surface-pad mb-4 fade-up">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h6 class="section-title"><i class="bi bi-star-fill"></i> Customer Satisfaction Rate</h6>
            <p style="font-size:13px;color:var(--text-2);margin:5px 0 0;">Based on Positive vs Negative sentiment ratio</p>
        </div>
        <div style="text-align:right;">
            @php
                $rateColor = $satisfactionRate >= 70 ? 'var(--success)' : ($satisfactionRate >= 40 ? 'var(--warning)' : 'var(--danger)');
                $rateLabel = $satisfactionRate >= 70 ? 'Good' : ($satisfactionRate >= 40 ? 'Average' : 'Needs improvement');
                $rateEmoji = $satisfactionRate >= 70 ? 'bi-emoji-smile-fill' : ($satisfactionRate >= 40 ? 'bi-emoji-neutral-fill' : 'bi-emoji-frown-fill');
            @endphp
            <div style="font-family:'Syne',sans-serif;font-size:42px;font-weight:800;color:{{ $rateColor }};line-height:1;">
                {{ $satisfactionRate }}%
            </div>
            <div style="font-size:12.5px;color:var(--text-2);margin-top:4px;">
                <i class="bi {{ $rateEmoji }}" style="color:{{ $rateColor }};"></i> {{ $rateLabel }}
            </div>
        </div>
    </div>
    {{-- Progress bar --}}
    <div style="background:var(--bg);border-radius:99px;height:8px;margin-top:16px;overflow:hidden;">
        <div style="width:{{ $satisfactionRate }}%;background:{{ $rateColor }};height:100%;border-radius:99px;transition:width 0.6s ease;"></div>
    </div>
</div>
@endif

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="surface surface-pad h-100 fade-up delay-1">
            <h6 class="section-title mb-4"><i class="bi bi-pie-chart-fill"></i> Sentiment Distribution</h6>
            <div style="position:relative;max-width:260px;margin:0 auto;">
                <canvas id="sentimentChart" height="240"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="surface surface-pad h-100 fade-up delay-2">
            <h6 class="section-title mb-4"><i class="bi bi-bar-chart-fill"></i> Ticket Status Overview</h6>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Monthly Chart --}}
<div class="surface surface-pad mb-4 fade-up delay-3">
    <h6 class="section-title mb-4"><i class="bi bi-graph-up-arrow"></i> Monthly Ticket Volume — {{ date('Y') }}</h6>
    <canvas id="monthlyChart" height="90"></canvas>
</div>

{{-- Top Categories --}}
@if(isset($topCategories) && count($topCategories))
<div class="surface surface-pad fade-up delay-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <h6 class="section-title"><i class="bi bi-lightbulb-fill"></i> Top Inquiry Categories</h6>
        <span style="font-size:11px;color:var(--text-3);background:var(--bg);padding:3px 9px;border-radius:6px;font-weight:600;">Predictive Insight</span>
    </div>
    <div class="row g-3">
        @foreach(array_slice($topCategories, 0, 6, true) as $category => $count)
        <div class="col-md-4">
            <div style="background:var(--bg);border-radius:var(--radius-md);padding:14px 16px;border:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:13.5px;font-weight:600;color:var(--text-1);">{{ $category }}</span>
                <span class="chip chip-neutral">{{ $count }}</span>
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

Chart.defaults.font.family = "'DM Sans', system-ui, sans-serif";
Chart.defaults.color = '#5A6485';

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
            backgroundColor: ['#FF3B5C', '#00C896', '#5B72F8'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 18, font: { size: 13 }, boxWidth: 10, boxHeight: 10 }
            }
        },
        cutout: '70%',
    }
});

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
            backgroundColor: ['rgba(91,114,248,0.12)', 'rgba(255,181,71,0.15)', 'rgba(0,200,150,0.12)'],
            borderColor: ['#5B72F8', '#FFB547', '#00C896'],
            borderWidth: 2,
            borderRadius: 10,
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
            borderColor: '#1A3FC4',
            backgroundColor: 'rgba(26,63,196,0.06)',
            fill: true,
            tension: 0.45,
            pointBackgroundColor: '#1A3FC4',
            pointBorderColor: '#fff',
            pointBorderWidth: 2.5,
            pointRadius: 5,
            pointHoverRadius: 7,
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
