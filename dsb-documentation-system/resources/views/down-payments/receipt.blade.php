@extends('layouts.app')

@section('title', 'Receipt {{ $payment->receipt_number }} - DSB Documentation System')

@section('content')
<style>
.receipt-container {
    padding: 24px;
    max-width: 900px;
    margin: 0 auto;
}

.receipt-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.receipt-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.btn-classic {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
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

.btn-secondary {
    background: var(--bg-elevated);
    color: var(--text-primary);
    border: 1px solid var(--border-strong);
}

/* Receipt Card */
.receipt-card {
    background: var(--bg-surface);
    border: 2px solid var(--border-strong);
    border-radius: 8px;
    overflow: hidden;
}

.receipt-top {
    background: linear-gradient(135deg, #1e4a8a 0%, #2563eb 100%);
    color: white;
    padding: 25px 30px;
    text-align: center;
}

.receipt-company {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: 1.5px;
    margin-bottom: 4px;
}

.receipt-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 2px;
    opacity: 0.9;
}

.receipt-number-display {
    background: rgba(255,255,255,0.15);
    display: inline-block;
    padding: 6px 20px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 15px;
    font-weight: 700;
    margin-top: 12px;
    letter-spacing: 1px;
}

.receipt-body {
    padding: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.info-item {
    padding: 14px;
    background: var(--bg-base);
    border-radius: 6px;
    border: 1px solid var(--border);
}

.info-label {
    font-size: 10px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
    margin-bottom: 6px;
}

.info-value {
    font-size: 14px;
    color: var(--text-primary);
    font-weight: 600;
}

.amount-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #34d399;
    border-radius: 8px;
    padding: 20px 25px;
    text-align: right;
    margin: 25px 0;
}

.amount-label {
    font-size: 11px;
    color: #065f46;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.amount-value {
    font-size: 32px;
    font-weight: 800;
    color: #059669;
    font-family: 'Courier New', monospace;
}

.notes-box {
    background: #fffbeb;
    border-left: 3px solid #f59e0b;
    padding: 15px;
    border-radius: 0 6px 6px 0;
    margin-top: 20px;
}

.notes-label {
    font-size: 10px;
    color: #92400e;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    margin-bottom: 6px;
}

.notes-text {
    font-size: 13px;
    color: #78350f;
    line-height: 1.5;
}

.receipt-footer {
    display: flex;
    justify-content: space-between;
    padding: 25px 30px;
    background: var(--bg-base);
    border-top: 1px solid var(--border);
}

.footer-item {
    text-align: center;
}

.footer-line {
    width: 180px;
    height: 2px;
    background: var(--border-strong);
    margin: 40px 0 8px;
}

.footer-label {
    font-size: 10px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.footer-value {
    font-size: 12px;
    color: var(--text-primary);
    font-weight: 600;
}

.action-bar {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

@media (prefers-color-scheme: light) {
    .amount-box {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }
}
</style>

<div class="receipt-container">
    <div class="receipt-header">
        <div>
            <h1 class="receipt-title">Payment Receipt</h1>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
                Official down payment receipt
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('down-payments.print', $payment) }}" target="_blank" class="btn-classic btn-primary">
                <i class="fa-solid fa-print"></i>
                Print Receipt
            </a>
            <a href="{{ route('down-payments.index') }}" class="btn-classic btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="receipt-card">
        <div class="receipt-top">
            <div class="receipt-company">DSB DOCUMENTATION SYSTEM</div>
            <div class="receipt-label">Official Payment Receipt</div>
            <div class="receipt-number-display">{{ $payment->receipt_number }}</div>
        </div>

        <div class="receipt-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Date Issued</div>
                    <div class="info-value">{{ $payment->payment_date->format('F d, Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Received From</div>
                    <div class="info-value">{{ $payment->client->client_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">{{ $payment->payment_method }}</div>
                </div>
                @if($payment->reference_number)
                <div class="info-item">
                    <div class="info-label">Reference Number</div>
                    <div class="info-value">{{ $payment->reference_number }}</div>
                </div>
                @endif
                <div class="info-item">
                    <div class="info-label">Recorded By</div>
                    <div class="info-value">{{ $payment->user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date Recorded</div>
                    <div class="info-value">{{ $payment->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>

            <div class="amount-box">
                <div class="amount-label">Total Amount Paid</div>
                <div class="amount-value">₱{{ number_format($payment->amount, 2) }}</div>
            </div>

            @if($payment->notes)
            <div class="notes-box">
                <div class="notes-label">Notes</div>
                <div class="notes-text">{{ $payment->notes }}</div>
            </div>
            @endif
        </div>

        <div class="receipt-footer">
            <div class="footer-item">
                <div class="footer-line"></div>
                <div class="footer-label">Authorized Signature</div>
                <div class="footer-value">{{ $payment->user->name }}</div>
            </div>
            <div class="footer-item">
                <div class="footer-line"></div>
                <div class="footer-label">Date Printed</div>
                <div class="footer-value">{{ now()->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('down-payments.print', $payment) }}" target="_blank" class="btn-classic btn-primary" style="flex: 1; justify-content: center;">
            <i class="fa-solid fa-print"></i>
            Print Receipt
        </a>
        <a href="{{ route('down-payments.index') }}" class="btn-classic btn-secondary">
            <i class="fa-solid fa-list"></i>
            View All Payments
        </a>
    </div>
</div>
@endsection
