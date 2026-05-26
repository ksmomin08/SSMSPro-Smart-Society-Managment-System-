<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.4;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo-section h1 {
            font-size: 24px;
            margin: 0;
            color: #1a56db;
            font-weight: 700;
        }
        .logo-section p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 22px;
            margin: 0;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .invoice-title p {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #666;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-table td {
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #718096;
            margin: 0 0 8px 0;
            font-weight: 700;
        }
        .details-box {
            background-color: #f7fafc;
            border: 1px solid #edf2f7;
            border-radius: 8px;
            padding: 15px;
            margin-right: 10px;
            min-height: 100px;
        }
        .details-box.right {
            margin-right: 0;
            margin-left: 10px;
        }
        .details-box p {
            margin: 4px 0;
            font-size: 13px;
        }
        .details-box strong {
            color: #2d3748;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f7fafc;
            border-bottom: 2px solid #edf2f7;
            padding: 12px 10px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #edf2f7;
            font-size: 13px;
            color: #4a5568;
        }
        .items-table tr.total-row td {
            border-bottom: none;
            font-weight: 700;
            font-size: 14px;
            color: #2d3748;
            padding-top: 20px;
        }
        .items-table tr.total-row td.amount-highlight {
            font-size: 18px;
            color: #1a56db;
        }
        .payment-meta {
            margin-top: 20px;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
        }
        .payment-meta h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #2d3748;
        }
        .meta-list {
            font-size: 12px;
            color: #718096;
        }
        .meta-list span {
            margin-right: 20px;
        }
        .meta-list strong {
            color: #4a5568;
        }
        .paid-stamp {
            position: absolute;
            top: 15px;
            right: 40px;
            border: 3px double #48bb78;
            color: #48bb78;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 15px;
            border-radius: 4px;
            transform: rotate(-10deg);
            opacity: 0.85;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
            font-size: 11px;
            color: #a0aec0;
        }
    </style>
</head>
<body>

<div class="container" style="position: relative;">
    <!-- Elegant Paid CSS Stamp -->
    <div class="paid-stamp">PAID</div>

    <table class="header-table">
        <tr>
            <td class="logo-section">
                <h1>{{ $payment->resident->society->name ?? 'Smart Society Management' }}</h1>
                <p>Ecosystem for Premium & Digital Housing societies</p>
                <p style="margin-top: 5px; color: #4a5568; font-weight: 500;">
                    {{ $payment->resident->society->address ?? 'Smart Society Hub, Main Ring Road, Suite 404' }}
                </p>
            </td>
            <td class="invoice-title">
                <h2>PAYMENT RECEIPT</h2>
                <p><strong>Receipt #:</strong> {{ $payment->receipt_number }}</p>
                <p><strong>Date:</strong> {{ $payment->created_at->format('d M, Y h:i A') }}</p>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <h3 class="section-title">PAID BY</h3>
                <div class="details-box">
                    <p><strong>Name:</strong> {{ $payment->resident->name }}</p>
                    <p><strong>Flat:</strong> 
                        @if($payment->resident->flats)
                            {{ $payment->resident->flats->building->building_name ?? '' }} - {{ $payment->resident->flats->flate_number }}
                        @else
                            Not Allocated
                        @endif
                    </p>
                    <p><strong>Email:</strong> {{ $payment->resident->email }}</p>
                    <p><strong>Phone:</strong> {{ $payment->resident->phone }}</p>
                </div>
            </td>
            <td>
                <h3 class="section-title">TRANSACTION DETAILS</h3>
                <div class="details-box right">
                    <p><strong>Payment Mode:</strong> {{ ucfirst($payment->payment_method) }} (Digital)</p>
                    <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                    <p><strong>Status:</strong> Success (Settled)</p>
                    <p><strong>Paid Time:</strong> {{ $payment->created_at->format('H:i:s') }}</p>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Description</th>
                <th style="width: 25%; text-align: right;">Billing Period</th>
                <th style="width: 25%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Society Maintenance Bill Charges</strong><br>
                    <span style="font-size: 11px; color:#718096;">Standard monthly operations fee, security, amenities, electricity.</span>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    {{ $payment->maintenance->month ?? 'N/A' }}
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    ₹ {{ number_format($payment->maintenance->amount ?? ($payment->amount - ($payment->maintenance->late_fee ?? 0)), 2) }}
                </td>
            </tr>
            @if($payment->maintenance && $payment->maintenance->late_fee > 0)
            <tr>
                <td>
                    <strong>Late Payment Surcharge</strong><br>
                    <span style="font-size: 11px; color:#718096;">Imposed due to delay beyond due date.</span>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    -
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    ₹ {{ number_format($payment->maintenance->late_fee, 2) }}
                </td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="2" style="text-align: right; padding-right: 15px;">TOTAL AMOUNT PAID:</td>
                <td style="text-align: right;" class="amount-highlight">₹ {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="payment-meta">
        <h4>Declaration & Note:</h4>
        <div class="meta-list">
            <p>This is a computer-generated transaction receipt and does not require a physical signature. The payment has been securely routed through fully verified transaction lines. Keep this document for reference during audits or parking/amenity validations.</p>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for being a responsible resident of our smart community! Powered by Smart Society SaaS.</p>
    </div>
</div>

</body>
</html>
