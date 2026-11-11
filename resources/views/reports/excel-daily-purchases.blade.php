<!DOCTYPE html>
<html lang="my">

<head>
    <meta charset="UTF-8">
    <title>သီးနှံဝယ်ယူမှုနှင့် အပ်နှံမှုစာရင်းချုပ်</title>
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
            background-color: #ffffff; /* နောက်ခံအဖြူရောင်သီးသန့် */
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border: none;
            background-color: transparent;
            color: #000000;
        }

        .subtitle {
            font-size: 13px;
            text-align: center;
            border: none;
            background-color: transparent;
            color: #000000;
        }

        /* ပုံမှန် Excel ခေါင်းစဉ် (Header) ပုံစံ - အရောင်မပါ၊ စာသားအမည်း */
        .section-header {
            background-color: #ffffff;
            font-weight: bold;
            font-size: 13px;
            color: #000000;
            text-align: left;
        }

        .table-header {
            background-color: #ffffff;
            font-weight: bold;
            color: #000000;
        }

        .total-row {
            background-color: #ffffff;
            font-weight: bold;
            color: #000000;
        }
    </style>
</head>

<body>

    <table>
        <!-- ခေါင်းစဉ် -->
        <tr>
            <td colspan="8" class="title">သီးနှံဝယ်ယူမှုနှင့် အပ်နှံမှုစာရင်းချုပ်</td>
        </tr>
        <tr>
            <td colspan="8" class="subtitle">
                ရက်စွဲ: {{ date('d-m-Y', strtotime($startDate)) }} မှ {{ date('d-m-Y', strtotime($endDate)) }} အထိ
            </td>
        </tr>
        <tr>
            <td colspan="8" style="border: none;"></td>
        </tr>

        <!-- ၂။ ပွဲရုံတွင် အပ်နှံထားဆဲ စာရင်းများ -->
        <tr>
            <td colspan="8" class="section-header">
                ပွဲရုံတွင် အပ်နှံထားဆဲ စာရင်းများ
            </td>
        </tr>
        <tr class="table-header">
            <th class="text-center" style="width: 40px;">စဉ်</th>
            <th>တောင်သူအမည်</th>
            <th>ကျေးရွာ</th>
            <th>အမျိုးအစား</th>
            <th class="text-right">အရေအတွက်</th>
            <th class="text-right">တင်း</th>
            <th class="text-right">မှန်းခြေဈေး</th>
            <th>မှတ်ချက်</th>
        </tr>

        @forelse($depositItems as $index => $item)
            @php
                $rawUnit = strtolower($item->unit ?? '');
                $displayUnit = $rawUnit === 'viss' ? 'ပိဿာ' : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit);
                $qty = (float) $item->quantity;
                $qtyPerBasket = (float) ($item->quantity_per_basket ?? 0);
                $basketCount = $qtyPerBasket > 0 ? $qty / $qtyPerBasket : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $item->farmer_name }}</td>
                <td>{{ $item->farmer_village ?? '-' }}</td>
                <td>{{ $item->crop_name }} ({{ $item->grade_name }})</td>
                <td class="text-right">{{ number_format($qty, 2) }} {{ $displayUnit }}</td>
                <td class="text-right fw-bold">{{ number_format($basketCount, 2) }}</td>
                <td class="text-right">{{ number_format($item->actual_buy_price, 2) }}</td>
                <td>{{ $item->note ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">အပ်နှံထားဆဲ စာရင်း မရှိပါ။</td>
            </tr>
        @endforelse

        @if ($depositItems->isNotEmpty())
            <tr class="total-row">
                <td colspan="4" class="text-right">အပ်နှံမှု စုစုပေါင်း:</td>
                <td class="text-right">
                    @php
                        $firstDepositUnit = strtolower($depositItems->first()->unit ?? '');
                    @endphp
                    @if ($firstDepositUnit === 'viss')
                        {{ number_format($totalDepositVissQuantity, 2) }} ပိဿာ
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">{{ number_format($totalDepositBasket, 2) }} တင်း</td>
                <td colspan="2" style="font-weight: normal;">ငွေရှင်းရန် ငွေပမာဏ မရှိသေးပါ</td>
            </tr>
        @endif

        <tr>
            <td colspan="8" style="border: none;"></td>
        </tr>

        <!-- ၁။ နေ့စဉ်ဝယ်ယူထားသော စာရင်းများ -->
        <tr>
            <td colspan="8" class="section-header">
                နေ့စဉ်ဝယ်ယူထားသော စာရင်းများ
            </td>
        </tr>
        <tr class="table-header">
            <th class="text-center" style="width: 40px;">စဉ်</th>
            <th>တောင်သူအမည်</th>
            <th>ကျေးရွာ</th>
            <th>အမျိုးအစား</th>
            <th class="text-right">အရေအတွက်</th>
            <th class="text-right">တင်း</th>
            <th class="text-right">ဝယ်ဈေး</th>
            <th class="text-right">သင့်ငွေ (ကျပ်)</th>
        </tr>

        @forelse($purchaseItems as $index => $item)
            @php
                $rawUnit = strtolower($item->unit ?? '');
                $displayUnit = $rawUnit === 'viss' ? 'ပိဿာ' : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit);
                $qty = (float) $item->quantity;
                $qtyPerBasket = (float) ($item->quantity_per_basket ?? 0);
                $basketCount = $qtyPerBasket > 0 ? $qty / $qtyPerBasket : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $item->farmer_name }}</td>
                <td>{{ $item->farmer_village ?? '-' }}</td>
                <td>{{ $item->crop_name }} ({{ $item->grade_name }})</td>
                <td class="text-right">{{ number_format($qty, 2) }} {{ $displayUnit }}</td>
                <td class="text-right fw-bold">{{ number_format($basketCount, 2) }}</td>
                <td class="text-right">{{ number_format($item->actual_buy_price, 2) }}</td>
                <td class="text-right fw-bold">{{ number_format($item->gross_amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">ဝယ်ယူထားသော စာရင်း မရှိပါ။</td>
            </tr>
        @endforelse

        <!-- အဝယ် စုစုပေါင်း tfoot ပုံစံ -->
        @if ($purchaseItems->isNotEmpty())
            <tr class="total-row">
                <td colspan="4" class="text-right">အဝယ် စုစုပေါင်း (Total):</td>
                <td class="text-right">
                    @php
                        $firstPurchaseUnit = strtolower($purchaseItems->first()->unit ?? '');
                    @endphp
                    @if ($firstPurchaseUnit === 'viss')
                        {{ number_format($totalVissQuantity, 2) }} ပိဿာ
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">{{ number_format($totalPurchaseBasket, 2) }} တင်း</td>
                <td class="text-right">-</td>
                <td class="text-right">{{ number_format($totalAmount, 2) }} ကျပ်</td>
            </tr>
        @endif
    </table>

</body>

</html>
