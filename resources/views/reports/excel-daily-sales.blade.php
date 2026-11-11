<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <title>သီးနှံရောင်းချမှု စာရင်းချုပ်</title>
    <style>
        body {
            font-family: 'Pyidaungsu', 'Myanmar Text', sans-serif;
            color: #000000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000000;
            padding: 6px 8px;
            font-size: 12px;
            color: #000000;
            background-color: #ffffff;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* အရောင်အသွေးမပါဘဲ ပုံမှန် အမည်း/အဖြူ ပုံစံသီးသန့် */
        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border: none;
            color: #000000;
            background-color: transparent;
        }
        .subtitle {
            font-size: 13px;
            text-align: center;
            color: #000000;
            border: none;
            background-color: transparent;
        }
        .header-row {
            background-color: #ffffff;
            font-weight: bold;
            text-align: center;
            color: #000000;
        }
        .total-row {
            font-weight: bold;
            background-color: #ffffff;
            color: #000000;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="9" class="title">သီးနှံရောင်းချမှု စာရင်းချုပ်</td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle">
                ရက်စွဲ: {{ date('d-m-Y', strtotime($startDate)) }} မှ {{ date('d-m-Y', strtotime($endDate)) }} အထိ
            </td>
        </tr>
        <tr><td colspan="9" style="border: none;"></td></tr>

        <!-- Table Header (အရောင်မပါ၊ စာသားအမည်းရောင် သက်သက်) -->
        <tr class="header-row">
            <th style="width: 40px;">စဉ်</th>
            <th>ဘောက်ချာအမှတ်</th>
            <th>ရက်စွဲ</th>
            <th>ဝယ်သူအမည်</th>
            <th>သီးနှံအမျိုးအစား</th>
            <th class="text-right">အရေအတွက်</th>
            <th class="text-right">တင်း</th>
            <th class="text-right">ရောင်းဈေး</th>
            <th class="text-right">သင့်ငွေ (ကျပ်)</th>
        </tr>

        @forelse($items as $index => $item)
        @php
            $rawUnit = strtolower($item->unit ?? '');
            $displayUnit = $rawUnit === 'viss' ? 'ပိဿာ' : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit);
            $qty = (float) $item->quantity;
            $itemQtyPerBasket = (float) ($item->quantity_per_basket ?? 0);
            $basketCount = $itemQtyPerBasket > 0 ? ($qty / $itemQtyPerBasket) : 0;
        @endphp
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ $item->invoice_no }}</td>
            <td class="text-center">{{ $item->sale_date }}</td>
            <td>{{ $item->customer_name }}</td>
            <td>{{ $item->crop_name }}</td>
            <td class="text-right">{{ number_format($qty, 2) }} {{ $displayUnit }}</td>
            <td class="text-right fw-bold">{{ number_format($basketCount, 2) }}</td>
            <td class="text-right">{{ number_format($item->sale_price, 2) }}</td>
            <td class="text-right fw-bold">{{ number_format($item->gross_amount, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center" style="color: #000000;">ရောင်းချထားသော စာရင်း မရှိပါ။</td>
        </tr>
        @endforelse

        <!-- Table Footer / Total Row (အရောင်မပါ၊ စာသားအမည်းရောင် သက်သက်) -->
        @if($items->isNotEmpty())
        <tr class="total-row">
            <td colspan="5" class="text-right">အရောင်း စုစုပေါင်း (Total):</td>
            <td class="text-right">
                @if(strtolower($items->first()->unit ?? '') === 'viss')
                    {{ number_format($totalSaleVissQuantity, 2) }} ပိဿာ
                @else
                    -
                @endif
            </td>
            <td class="text-right">{{ number_format($totalSaleBasket, 2) }} တင်း</td>
            <td class="text-right">-</td>
            <td class="text-right">{{ number_format($totalAmount, 2) }} ကျပ်</td>
        </tr>
        @endif
    </table>
</body>
</html>
