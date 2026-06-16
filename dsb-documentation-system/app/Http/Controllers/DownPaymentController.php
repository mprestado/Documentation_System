<?php

namespace App\Http\Controllers;

use App\Models\DownPayment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownPaymentController extends Controller
{
    /**
     * Display down payments list (for non-admin, only their own)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is admin/owner
        $isAdmin = $user->role === 'admin' || $user->role === 'owner';
        
        $query = DownPayment::with(['client', 'user']);
        
        // Non-admin users can only see their own payments
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }
        
        $payments = $query->latest()->get();
        
        return view('down-payments.index', compact('payments', 'isAdmin'));
    }

    /**
     * Show form to create new payment
     */
    public function create()
    {
        $clients = Client::orderBy('client_name')->get();
        return view('down-payments.create', compact('clients'));
    }

    /**
     * Store a new down payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payment = DownPayment::create([
            'client_id' => $validated['client_id'],
            'user_id' => auth()->id(),
            'receipt_number' => DownPayment::generateReceiptNumber(),
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('down-payments.receipt', $payment)
            ->with('success', 'Down payment recorded successfully!');
    }

    /**
     * Show printable receipt
     */
    public function showReceipt(DownPayment $payment)
    {
        return view('down-payments.receipt', compact('payment'));
    }

    /**
     * Print receipt (opens print dialog)
     */
    public function printReceipt(DownPayment $payment)
    {
        return view('down-payments.print-receipt', compact('payment'));
    }

    /**
     * Delete a payment (admin only)
     */
    public function destroy(DownPayment $payment)
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin' || $user->role === 'owner';
        
        // Only admin/owner or the creator can delete
        if (!$isAdmin && $payment->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $payment->delete();
        
        return redirect()->route('down-payments.index')
            ->with('success', 'Payment record deleted.');
    }
}
