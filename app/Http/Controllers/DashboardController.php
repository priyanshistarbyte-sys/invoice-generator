<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalInvoices = \App\Models\Invoice::where('created_by', auth()->id())->count();
        $totalRevenue = \App\Models\Invoice::where('created_by', auth()->id())->sum('paid_amount');
        $pendingInvoices = \App\Models\Invoice::where('created_by', auth()->id())
            ->whereRaw('paid_amount < (SELECT SUM(total_amount) FROM invoice_items WHERE invoice_id = invoices.id)')
            ->count();
        $paidInvoices = \App\Models\Invoice::where('created_by', auth()->id())
            ->whereRaw('paid_amount >= (SELECT SUM(total_amount) FROM invoice_items WHERE invoice_id = invoices.id)')
            ->count();
        
        $query = \App\Models\Invoice::with(['customer_name', 'items'])
            ->where('created_by', auth()->id());
        
        if ($request->filled('month')) {
            $query->whereYear('created_at', substr($request->month, 0, 4))
                  ->whereMonth('created_at', substr($request->month, 5, 2));
        } else {
            $query->limit(5);
        }
        
        $recentInvoices = $query->orderBy('created_at', 'desc')->get();
        $selectedMonth = $request->month;
            
        return view('dashboard', compact('totalInvoices', 'totalRevenue', 'pendingInvoices', 'paidInvoices', 'recentInvoices', 'selectedMonth'));
    }

   
    public function loginWithInvoice(Request $request, $id)
    {
       
        $user = \App\Models\User::find($id);
        
        if (!$user) {
            abort(404, 'User not found');
        }
        // Verify token from main-site
        $expectedToken = hash_hmac('sha256', $id, env('APP_KEY'));
        if (!hash_equals($expectedToken, $request->query('token'))) {
            abort(403, 'Invalid signature.');
        }
        // Log in user
        \Auth::login($user, true);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
    }


    
}
