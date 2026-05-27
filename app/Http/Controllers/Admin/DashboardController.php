<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin/DashboardController — Updated for revised capstone
 *
 * Changes from original:
 * - Sentiment: Urgent/Frustrated → Positive/Negative/Neutral
 * - Added Service Analytics data (M15) to analytics()
 * - Added resolution time, top inquiry categories
 */
class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets      = Ticket::count();
        $pendingTickets    = Ticket::where('status', 'Pending')->count();
        $processingTickets = Ticket::where('status', 'Processing')->count();
        $resolvedTickets   = Ticket::where('status', 'Resolved')->count();

        // Updated: Positive / Negative / Neutral (panel requirement)
        $positiveTickets = Ticket::where('sentiment', 'Positive')->where('status', '!=', 'Resolved')->count();
        $negativeTickets = Ticket::where('sentiment', 'Negative')->where('status', '!=', 'Resolved')->count();
        $neutralTickets  = Ticket::where('sentiment', 'Neutral')->where('status', '!=', 'Resolved')->count();

        $recentTickets = Ticket::with('user')
            ->orderByRaw("FIELD(sentiment, 'Negative', 'Neutral', 'Positive')")
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalCustomers = User::where('role', 'customer')->count();
        $totalFaqs      = Faq::count();

        return view('admin.dashboard', compact(
            'totalTickets', 'pendingTickets', 'processingTickets', 'resolvedTickets',
            'positiveTickets', 'negativeTickets', 'neutralTickets',
            'recentTickets', 'totalCustomers', 'totalFaqs'
        ));
    }

    public function analytics()
    {
        // M15 — Service Analytics: Sentiment Breakdown
        $sentimentData = [
            'Positive' => Ticket::where('sentiment', 'Positive')->count(),
            'Negative' => Ticket::where('sentiment', 'Negative')->count(),
            'Neutral'  => Ticket::where('sentiment', 'Neutral')->count(),
        ];

        // Status breakdown
        $statusData = [
            'Pending'    => Ticket::where('status', 'Pending')->count(),
            'Processing' => Ticket::where('status', 'Processing')->count(),
            'Resolved'   => Ticket::where('status', 'Resolved')->count(),
        ];

        // Monthly ticket volume (for bar/line chart)
        $monthlyTickets = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // M15 — Average resolution time per month (in hours)
        $avgResolutionTime = Ticket::selectRaw('
                MONTH(created_at) as month,
                ROUND(AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)), 1) as avg_hours
            ')
            ->where('status', 'Resolved')
            ->whereNotNull('resolved_at')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // M15 — Daily ticket volume (last 14 days for sparkline)
        $dailyVolume = Ticket::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // M14 — Top inquiry categories (for predictive insights)
        $topCategories = $this->getTopInquiryCategories();

        // Satisfaction rate: Positive / (Positive + Negative) * 100
        $total = $sentimentData['Positive'] + $sentimentData['Negative'];
        $satisfactionRate = $total > 0
            ? round(($sentimentData['Positive'] / $total) * 100, 1)
            : null;

        return view('admin.analytics', compact(
            'sentimentData', 'statusData', 'monthlyTickets',
            'avgResolutionTime', 'dailyVolume', 'topCategories', 'satisfactionRate'
        ));
    }

    private function getTopInquiryCategories(): array
    {
        $categories = [
            'Warranty'   => ['warranty', 'warrant', 'guarantee'],
            'Repair'     => ['repair', 'fix', 'broken', 'screen', 'battery'],
            'Payment'    => ['payment', 'gcash', 'installment', 'pay'],
            'Return'     => ['return', 'refund', 'exchange'],
            'Stock'      => ['stock', 'available', 'iphone', 'samsung'],
            'Trade-in'   => ['trade', 'second hand', 'buy back'],
            'Location'   => ['location', 'address', 'where', 'branch'],
            'Others'     => [],
        ];

        $tickets = Ticket::selectRaw('LOWER(subject) as subject, LOWER(description) as description')->get();
        $counts  = array_fill_keys(array_keys($categories), 0);

        foreach ($tickets as $ticket) {
            $combined = $ticket->subject . ' ' . $ticket->description;
            $matched  = false;
            foreach ($categories as $category => $keywords) {
                if ($category === 'Others') continue;
                foreach ($keywords as $kw) {
                    if (str_contains($combined, $kw)) {
                        $counts[$category]++;
                        $matched = true;
                        break 2;
                    }
                }
            }
            if (!$matched) $counts['Others']++;
        }

        arsort($counts);
        return $counts;
    }
}