@extends('layouts.app')

@section('title', 'Record Down Payment - DSB Documentation System')

@section('content')
<style>
.dp-container {
    padding: 24px;
    max-width: 700px;
    margin: 0 auto;
}

.dp-header {
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

/* Classic Form Card */
.form-card {
    background: var(--bg-surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 24px;
}

.form-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #1e4a8a, #2563eb);
}

.form-group {
    margin-bottom: 18px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 11px 13px;
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text-primary);
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 2px var(--accent-glow);
}

.form-textarea {
    resize: vertical;
    min-height: 90px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

.btn-classic {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 20px;
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

/* Receipt Preview Box */
.receipt-preview {
    background: var(--bg-base);
    border: 1px dashed var(--border-strong);
    border-radius: 6px;
    padding: 16px;
    margin-top: 8px;
}

.preview-row {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    padding: 6px 0;
    border-bottom: 1px dotted var(--border);
}

.preview-row:last-child {
    border-bottom: none;
}

.preview-label {
    color: var(--text-muted);
}

.preview-value {
    color: var(--text-primary);
    font-weight: 600;
}

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
    
    .form-card::before {
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
    }
}
</style>

<div class="dp-container">
    <div class="dp-header">
        <h1 class="dp-title">Record Down Payment</h1>
        <p class="dp-subtitle">Enter client payment details to generate receipt</p>
    </div>

    <div class="form-card" style="position: relative;">
        <form action="{{ route('down-payments.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Client <span style="color: #ef4444;">*</span></label>
                <select name="client_id" class="form-select" required onchange="updatePreview()">
                    <option value="">Select Client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" data-name="{{ $client->client_name }}">
                            {{ $client->client_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Amount (₱) <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="amount" class="form-input" step="0.01" min="0.01" 
                           placeholder="0.00" required onchange="updatePreview()">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Payment Date <span style="color: #ef4444;">*</span></label>
                    <input type="date" name="payment_date" class="form-input" 
                           value="{{ date('Y-m-d') }}" required onchange="updatePreview()">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Method <span style="color: #ef4444;">*</span></label>
                <select name="payment_method" class="form-select" required onchange="updatePreview()">
                    <option value="">Select Method...</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Check">Check</option>
                    <option value="GCash">GCash</option>
                    <option value="PayMaya">PayMaya</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Reference Number</label>
                <input type="text" name="reference_number" class="form-input" 
                       placeholder="Check #, Transaction ID, etc." onchange="updatePreview()">
                <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">
                    Optional. For bank transfers, checks, or digital wallet transactions.
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea" 
                          placeholder="Additional remarks about this payment..."></textarea>
            </div>

            <!-- Receipt Preview -->
            <div class="receipt-preview">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); 
                            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                    Receipt Preview
                </div>
                <div class="preview-row">
                    <span class="preview-label">Receipt No.</span>
                    <span class="preview-value" id="preview-receipt">DP-{{ date('Ymd') }}-####</span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Client</span>
                    <span class="preview-value" id="preview-client">-</span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Amount</span>
                    <span class="preview-value" id="preview-amount">₱0.00</span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Method</span>
                    <span class="preview-value" id="preview-method">-</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-classic btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save & Generate Receipt
                </button>
                <a href="{{ route('down-payments.index') }}" class="btn-classic btn-secondary">
                    <i class="fa-solid fa-xmark"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updatePreview() {
    const clientSelect = document.querySelector('select[name="client_id"]');
    const amountInput = document.querySelector('input[name="amount"]');
    const methodSelect = document.querySelector('select[name="payment_method"]');
    const refInput = document.querySelector('input[name="reference_number"]');
    
    const clientName = clientSelect.options[clientSelect.selectedIndex]?.dataset?.name || '-';
    const amount = parseFloat(amountInput.value) || 0;
    const method = methodSelect.value || '-';
    
    document.getElementById('preview-client').textContent = clientName;
    document.getElementById('preview-amount').textContent = '₱' + amount.toFixed(2);
    document.getElementById('preview-method').textContent = method;
}
</script>
@endsection
