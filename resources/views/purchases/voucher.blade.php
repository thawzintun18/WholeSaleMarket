blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher - {{ $purchase->invoice_no }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f1f3f5;
            font-family: Arial, sans-serif;
            color: #222;
            font-size: 13px;
        }

        .voucher {
            background: #fff;
            max-width: 900px;
            margin: 25px auto;
            padding: 35px;
            border: 1px solid #ddd;
        }

        .voucher-header {
            text-align: center;
            padding-bottom: 18px;
            border-bottom: 2px solid #222;
            margin-bottom: 20px;
        }

        .voucher-header h2 {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .voucher-title {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 25px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            gap: 8px;
        }

        .info-item span:first-child {
            color: #666;
            min-width: 105px;
        }

        .table th {
            background: #f1f3f5;
            white-space: nowrap;
        }

        .table td,
        .table th {
            padding: 9px 7px;
            vertical-align: middle;
        }

        .summary {
            width: 340px;
            max-width: 100%;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 7px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-row.total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #222;
            border-bottom: 2px solid #222;
        }

        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 25px;
            margin-top: 65px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #555;
            padding-top: 8px;
        }

        .voucher-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
        }

        .no-print {
            max-width: 900px;
            margin: 20px auto 0;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            body {
                background: #fff;
                font-size: 11px;
            }

            .voucher {
                max-width: none;
                margin: 0;
                padding: 0;
                border: none;
            }

            .no-print {
                display: none !important;
            }

            .table {
                break-inside: auto;
            }

            tr {
                break-inside: avoid;
            }

            .signatures {
                break-inside: avoid;
            }

            .table th {
                background: #eee !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

<div class="no-print">
    <a href="{{ route('purchases.show', $purchase->id) }}"
       class="btn btn-outline-secondary btn-sm">
        Back to Purchase
    </a>

    <button onclick="window.print()" class="btn btn-success btn-sm">
        <i class="bi bi-printer"></i> Print Voucher
    </button>
</div>

<div class="voucher">

    {{-- Business Header --}}
    <div class="voucher-header">
        <h2>WHOLESALE MARKET</h2>
        <div>Farmer Purchase & Payment Voucher</div>
        <div class="text-muted mt-1">
            Address: ______________________________
        </div>
        <div class="text-muted">
            Phone: ________________________________
        </div>
    </div>

    <div class="voucher-title">PURCHASE VOUCHER</div>

    {{-- Invoice Information --}}
    <div class="info-grid">
        <div class="info-item">
            <span>Invoice No.</span>
            <strong>{{ $purchase->invoice_no }}</strong>
        </div>

        <div class="info-item">
            <span>Purchase Date</span>
            <strong>
                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y h:i A') }}
            </strong>
        </div>

        <div class="info-item">
            <span>Farmer Name</span>
            <strong>{{ $purchase->farmer->name ?? '-' }}</strong>
        </div>

        <div class="info-item">
            <span>Phone</span>
            <strong>{{ $purchase->farmer->phone ?? '-' }}</strong>
        </div>

        <div class="info-item">
            <span>Village</span>
            <strong>{{ $purchase->farmer->village ?? '-' }}</strong>
        </div>

        <div class="info-item">
            <span>Status</span>
            <strong>{{ $purchase->status }}</strong>
        </div>
    </div>

    {{-- Crop Details --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Crop</th>
                <th>Grade</th>
                <th class="text-end">Quantity</th>
                <th>Unit</th>
                <th class="text-end">Buy Price</th>
                <th class="text-end">Gross</th>
                <th class="text-end">Commission</th>
                <th class="text-end">Car Fee</th>
                <th class="text-end">Net</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($purchase->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->crop->crop_name ?? '-' }}</td>
                    <td>{{ $item->grade->grade_name ?? '-' }}</td>

                    <td class="text-end">
                        {{ number_format($item->quantity, 3) }}
                    </td>

                    <td>{{ $item->unit }}</td>

                    <td class="text-end">
                        {{ number_format($item->actual_buy_price, 2) }}
                    </td>

                    <td class="text-end">
                        {{ number_format($item->gross_amount, 2) }}
                    </td>

                    <td class="text-end">
                        {{ number_format($item->commission_amount, 2) }}
                    </td>

                    <td class="text-end">
                        {{ number_format($item->car_fee_amount, 2) }}
                    </td>

                    <td class="text-end">
                        {{ number_format($item->net_amount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Payment History --}}
    <h6 class="fw-bold mt-4 mb-2">Payment Details</h6>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Payment Type</th>
                <th>Method</th>
                <th>Note</th>
                <th class="text-end">Amount (Ks)</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($purchase->payments as $payment)
                <tr>
                    <td>
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y h:i A') }}
                    </td>

                    <td>{{ $payment->payment_type }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->note ?: '-' }}</td>

                    <td class="text-end">
                        {{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        No payment recorded.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Financial Summary --}}
    <div class="summary mt-4">

        <div class="summary-row">
            <span>Total Amount</span>
            <strong>{{ number_format($purchase->total_amount, 2) }} Ks</strong>
        </div>

        <div class="summary-row">
            <span>Total Deduction</span>
            <strong>{{ number_format($purchase->total_deduction, 2) }} Ks</strong>
        </div>

        <div class="summary-row total">
            <span>Net Amount</span>
            <span>{{ number_format($purchase->net_amount, 2) }} Ks</span>
        </div>

        <div class="summary-row">
            <span>Total Paid</span>
            <strong>{{ number_format($purchase->total_paid, 2) }} Ks</strong>
        </div>

        <div class="summary-row">
            <span>Balance Due</span>
            <strong>{{ number_format($purchase->balance_amount, 2) }} Ks</strong>
        </div>

    </div>

    @if ($purchase->note)
        <div class="mt-4">
            <strong>Note:</strong> {{ $purchase->note }}
        </div>
    @endif

    {{-- Signatures --}}
    <div class="signatures">
        <div class="signature-line">Farmer Signature</div>
        <div class="signature-line">Received By</div>
        <div class="signature-line">Authorized By</div>
    </div>

    <div class="voucher-footer">
        Thank you for your business.
        <div class="small mt-1">
            Printed: {{ now()->format('d-m-Y h:i A') }}
        </div>
    </div>

</div>

</body>
</html>

