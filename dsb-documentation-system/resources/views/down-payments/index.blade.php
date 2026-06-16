@extends('layouts.app')

@section('title', 'Down Payments - DSB Documentation System')

@section('content')
<style>
/* Classic Document / Legacy System Theme */
.dp-container {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
}

.dp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.dp-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.3px;
}

.dp-subtitle {
    font-size: 13px;
    color: var(--text-secondary);
    margin-top: 4px;
}

.btn-classic {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
    border: 1px solid transparent;
    font-family: 'Inter', sans-serif;
}

.btn-primary {
    background: linear-gradient(135deg, #1e4a8a 0%, #2563eb 100%);
    color: white;
    border-color: rgba(37,99,235,0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--bg-elevated);
    color: var(--text-primary);
    border: 1px solid var(--border-strong);
}

.btn-secondary:hover {
    background: var(--bg-hover);
    border-color: var(--text-muted);
}

/* Receipt Card Grid */
.receipts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 16px;
}

.receipt-card {
    background: var(--bg-surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 18px;
    transition: all 0.15s;
    position: relative;
    overflow: hidden;
}

.receipt-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #1e4a8a, #2563eb);
}

.receipt-card:hover {
    border-color: var(--border-strong);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}

.receipt-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
}

.receipt-number {
    font-size: 14px;
    font-weight: 700;
    color: var(--accent-light);
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}

.receipt-date {
    font-size: 11px;
    color: var(--text-muted);
}

.receipt-client {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px dashed var(--border);
}

.receipt-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.detail-label {
    font-size: 10px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 13px;
    color: var(--text-primary);
    font-weight: 500;
}

.receipt-amount {
    font-size: 24px;
    font-weight: 800;
    color: #34d399;
    text-align: right;
    margin-top: 8px;
    font-family: 'Courier New', monospace;
}

.receipt-actions {
    display: flex;
    gap: 8px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid var(--border);
}

.btn-small {
    flex: 1;
    padding: 7px 10px;
    font-size: 11px;
    border-radius: 5px;
    text-align: center;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 16px;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

/* Print Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 100;
}

.modal-overlay.visible {
    display: flex;
}

.modal-content {
    background: var(--bg-surface);
    border: 1px solid var(--border-strong);
    border-radius: 12px;
    padding: 24px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 20px;
    cursor: pointer;
    padding: 4px;
}

.modal-close:hover {
    color: var(--text-primary);
}

/* Form Styles */
.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 10px 12px;
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text-primary);
    font-size: 13px;
    font-family: 'Inter', sans-serif;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 2px var(--accent-glow);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

/* Light Mode Support */
@media (prefers-color-scheme: light) {
    :root {
        --bg-base: #f5f7fa;
        --bg-surface: #ffffff;
        --bg-elevated: #eef2f6;
        --border: #d1d5db;
        --border-strong: #9ca3af;
        --text-primary: #1f2937;
        --text-secondary: #4b5563;
        --text-muted: #9ca3af;
    }
    
    .receipt-card::before {
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
    }
    
    .receipt-amount {
        color: #059669;
    }
}
</style>

<div class="dp-container">
    <div class="dp-header">
        <div>
            <h1 class="dp-title">Down Payment Receipts</h1>
            <p class="dp-subtitle">Record and manage client down payments</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('down-payments.create') }}" class="btn-classic btn-primary">
                <i class="fa-solid fa-plus"></i>
                New Payment
            </a>
            <a href="{{ route('dashboard') }}" class="btn-classic btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    @if($payments->count() > 0)
        <div class="receipts-grid">
            @foreach($payments as $payment)
                <div class="receipt-card">
                    <div class="receipt-header">
                        <span class="receipt-number">{{ $payment->receipt_number }}</span>
                        <span class="receipt-date">{{ $payment->payment_date->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="receipt-client">
                        {{ $payment->client->client_name }}
                    </div>
                    
                    <div class="receipt-details">
                        <div class="detail-item">
                            <span class="detail-label">Payment Method</span>
                            <span class="detail-value">{{ $payment->payment_method }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Recorded By</span>
                            <span class="detail-value">{{ $payment->user->name }}</span>
                        </div>
                        @if($payment->reference_number)
                            <div class="detail-item" style="grid-column: span 2;">
                                <span class="detail-label">Reference Number</span>
                                <span class="detail-value">{{ $payment->reference_number }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="receipt-amount">
                        ₱{{ number_format($payment->amount, 2) }}
                    </div>
                    
                    <div class="receipt-actions">
                        <a href="{{ route('down-payments.receipt', $payment) }}" class="btn-classic btn-secondary btn-small">
                            <i class="fa-solid fa-eye"></i>
                            View
                        </a>
                        <a href="{{ route('down-payments.print', $payment) }}" target="_blank" class="btn-classic btn-primary btn-small">
                            <i class="fa-solid fa-print"></i>
                            Print
                        </a>
                        @if($isAdmin || $payment->user_id === auth()->id())
                            <form action="{{ route('down-payments.destroy', $payment) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Delete this payment record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-classic btn-secondary btn-small" style="width: 100%; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #ef4444;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <h3>No Down Payments Recorded Yet</h3>
            <p>Start by recording a new client down payment.</p>
            <a href="{{ route('down-payments.create') }}" class="btn-classic btn-primary" style="margin-top: 16px;">
                <i class="fa-solid fa-plus"></i>
                Record Payment
            </a>
        </div>
    @endif
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    alert('{{ session("success") }}');
});
</script>
@endif
@endsection
