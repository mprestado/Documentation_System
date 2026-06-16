<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt {{ $payment->receipt_number }} - DSB Documentation System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            padding: 40px 20px;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .receipt {
            padding: 50px;
            border: 2px solid #1e4a8a;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 3px double #1e4a8a;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: 700;
            color: #1e4a8a;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .receipt-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e4a8a;
            text-align: center;
            margin: 20px 0;
            font-family: 'Courier Prime', monospace;
        }
        
        .receipt-number-box {
            background: #1e4a8a;
            color: white;
            padding: 8px 20px;
            display: inline-block;
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        
        .receipt-body {
            margin-top: 30px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dotted #ccc;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .info-value {
            font-size: 15px;
            color: #1f2937;
            font-weight: 600;
            text-align: right;
        }
        
        .amount-section {
            margin-top: 35px;
            padding: 25px;
            background: #f8fafc;
            border: 2px solid #1e4a8a;
            text-align: right;
        }
        
        .amount-label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .amount-value {
            font-size: 36px;
            font-weight: 800;
            color: #1e4a8a;
            font-family: 'Courier Prime', monospace;
        }
        
        .notes-section {
            margin-top: 25px;
            padding: 15px;
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
        }
        
        .notes-label {
            font-size: 11px;
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
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 30px;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #1e4a8a;
            margin-top: 50px;
            padding-top: 8px;
        }
        
        .signature-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .signature-name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            margin-top: 4px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #9ca3af;
        }
        
        .print-btn-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .print-btn {
            background: #1e4a8a;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        
        .print-btn:hover {
            background: #2563eb;
        }
        
        @media print {
            body { 
                background: white; 
                padding: 0; 
            }
            .print-btn-container { 
                display: none; 
            }
            .receipt-container { 
                box-shadow: none; 
            }
        }
    </style>
</head>
<body>
    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print Receipt
        </button>
    </div>
    
    <div class="receipt-container">
        <div class="receipt">
            <div class="receipt-header">
                <div class="company-name">DSB DOCUMENTATION SYSTEM</div>
                <div class="company-tagline">Professional Document Processing Services</div>
                <div class="receipt-title">OFFICIAL RECEIPT</div>
                <div class="receipt-number-box">{{ $payment->receipt_number }}</div>
            </div>
            
            <div class="receipt-body">
                <div class="info-row">
                    <span class="info-label">Date Issued</span>
                    <span class="info-value">{{ $payment->payment_date->format('F d, Y') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Received From</span>
                    <span class="info-value">{{ $payment->client->client_name }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value">{{ $payment->payment_method }}</span>
                </div>
                
                @if($payment->reference_number)
                <div class="info-row">
                    <span class="info-label">Reference Number</span>
                    <span class="info-value">{{ $payment->reference_number }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Recorded By</span>
                    <span class="info-value">{{ $payment->user->name }}</span>
                </div>
                
                @if($payment->notes)
                <div class="notes-section">
                    <div class="notes-label">Notes</div>
                    <div class="notes-text">{{ $payment->notes }}</div>
                </div>
                @endif
            </div>
            
            <div class="amount-section">
                <div class="amount-label">Amount Paid</div>
                <div class="amount-value">₱{{ number_format($payment->amount, 2) }}</div>
            </div>
            
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-label">Authorized Signature</div>
                        <div class="signature-name">{{ $payment->user->name }}</div>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-label">Date Printed</div>
                        <div class="signature-name">{{ now()->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                This is a computer-generated receipt. No signature required.<br>
                Thank you for your business!
            </div>
        </div>
    </div>
    
    <script>
        // Auto-print on load if coming from "Print" button
        window.addEventListener('load', function() {
            // Uncomment the line below to enable auto-print
            // window.print();
        });
    </script>
</body>
</html>
