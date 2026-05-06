<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Faq;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();
        $pendingTickets = Ticket::where('status', 'Pending')->count();
        $processingTickets = Ticket::where('status', 'Processing')->count();
        $resolvedTickets = Ticket::where('status', 'Resolved')->count();

        $urgentTickets = Ticket::where('sentiment', 'Urgent')->where('status', '!=', 'Resolved')->count();
        $frustratedTickets = Ticket::where('sentiment', 'Frustrated')->where('status', '!=', 'Resolved')->count();
        $neutralTickets = Ticket::where('sentiment', 'Neutral')->where('status', '!=', 'Resolved')->count();

        $recentTickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalCustomers = User::where('role', 'customer')->count();
        $totalFaqs = Faq::count();

        return view('admin.dashboard', compact(
            'totalTickets', 'pendingTickets', 'processingTickets', 'resolvedTickets',
            'urgentTickets', 'frustratedTickets', 'neutralTickets',
            'recentTickets', 'totalCustomers', 'totalFaqs'
        ));
    }

    public function analytics()
    {
        $sentimentData = [
            'Urgent' => Ticket::where('sentiment', 'Urgent')->count(),
            'Frustrated' => Ticket::where('sentiment', 'Frustrated')->count(),
            'Neutral' => Ticket::where('sentiment', 'Neutral')->count(),
        ];

        $statusData = [
            'Pending' => Ticket::where('status', 'Pending')->count(),
            'Processing' => Ticket::where('status', 'Processing')->count(),
            'Resolved' => Ticket::where('status', 'Resolved')->count(),
        ];

        $monthlyTickets = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.analytics', compact('sentimentData', 'statusData', 'monthlyTickets'));
    }
}