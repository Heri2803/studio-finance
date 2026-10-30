<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Slip Gaji - {{ $payrollSlip->employee->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2D2B38;
            margin: 0;
            padding: 20px;
            background-color: #FFFFFF;
            font-size: 12px;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            border: 2px solid #DDD6FE;
            border-radius: 12px;
            padding: 16px;
            background-color: #FFFEFA;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #2D2B38;
            margin: 0;
        }
        .subtitle {
            font-size: 10px;
            color: #716D7D;
            margin: 2px 0 0 0;
        }
        .period-badge {
            background-color: #EDE9FE;
            color: #6D28D9;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
            border: 1px solid #DDD6FE;
        }
        .divider {
            border-top: 1px dashed #DDD6FE;
            margin: 12px 0;
        }
        .info-table td {
            padding: 4px 0;
            font-size: 11px;
        }
        .info-label {
            color: #716D7D;
            width: 110px;
            font-weight: bold;
        }
        .info-value {
            color: #2D2B38;
            font-weight: bold;
        }
        .breakdown-table th {
            background-color: #EDE9FE;
            color: #4C1D95;
            font-size: 10px;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
            border-radius: 4px;
        }
        .breakdown-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 11px;
        }
        .amount-col {
            text-align: right;
            font-weight: bold;
        }
        .total-box {
            background-color: #D1FAE5;
            border: 1.5px solid #A7F3D0;
            border-radius: 8px;
            padding: 10px 12px;
            margin-top: 12px;
        }
        .total-label {
            font-size: 11px;
            font-weight: bold;
            color: #065F46;
        }
        .total-value {
            font-size: 16px;
            font-weight: bold;
            color: #065F46;
            text-align: right;
        }
        .stamp-box {
            text-align: right;
            margin-top: 10px;
            font-size: 10px;
            color: #716D7D;
        }
        .stamp-mark {
            display: inline-block;
            border: 1.5px dashed #10B981;
            color: #047857;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="title">🐾 STUDIO KEMANG CREATIVE</div>
                    <div class="subtitle">Video Production • Social Media & Digital Ads Hub • Kemang, Jakarta Selatan</div>
                </td>
                <td style="text-align: right;">
                    <div class="period-badge">Periode: {{ $payrollSlip->period }}</div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Employee Info -->
        <table class="info-table">
            <tr>
                <td class="info-label">Nama Pekerja</td>
                <td class="info-value">: {{ $payrollSlip->employee->name }}</td>
                <td class="info-label">Tipe Pekerja</td>
                <td class="info-value">: {{ $payrollSlip->employee->type_label }}</td>
            </tr>
            <tr>
                <td class="info-label">Jabatan / Role</td>
                <td class="info-value">: {{ $payrollSlip->employee->role }}</td>
                <td class="info-label">Rekening Bank</td>
                <td class="info-value">: {{ $payrollSlip->employee->bank_name }} - {{ $payrollSlip->employee->bank_account_number ?? '-' }} (a.n {{ $payrollSlip->employee->bank_account_name ?? $payrollSlip->employee->name }})</td>
            </tr>
            <tr>
                <td class="info-label">Nomor Slip</td>
                <td class="info-value">: #SLIP-{{ str_pad($payrollSlip->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="info-label">Status Bayar</td>
                <td class="info-value">: <strong>{{ $payrollSlip->status === 'PAID' ? 'LUNAS (PAID 🐾)' : 'PENDING' }}</strong></td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Salary Breakdown -->
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th>Komponen Penerimaan (Earnings)</th>
                    <th class="amount-col">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if($payrollSlip->employee->employment_type === 'FREELANCE')
                            Honor Sesi Shooting ({{ $payrollSlip->sessions_count }} sesi × Rp {{ number_format($payrollSlip->employee->base_salary_or_rate, 0, ',', '.') }})
                        @else
                            Gaji Pokok / Retainer Bulanan
                        @endif
                    </td>
                    <td class="amount-col">Rp {{ number_format($payrollSlip->basic_amount, 0, ',', '.') }}</td>
                </tr>
                @if($payrollSlip->allowance > 0)
                <tr>
                    <td>Tunjangan Operasional / Uang Makan & Transport</td>
                    <td class="amount-col">+ Rp {{ number_format($payrollSlip->allowance, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($payrollSlip->bonus > 0)
                <tr>
                    <td>Bonus Kinerja / Lembur / FYP Reward</td>
                    <td class="amount-col">+ Rp {{ number_format($payrollSlip->bonus, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($payrollSlip->deduction > 0)
                <tr>
                    <td style="color: #991B1B;">Potongan Kasbon / Absensi</td>
                    <td class="amount-col" style="color: #991B1B;">- Rp {{ number_format($payrollSlip->deduction, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Take Home Pay Box -->
        <table class="total-box">
            <tr>
                <td class="total-label">TOTAL GAJI BERSIH (TAKE HOME PAY)</td>
                <td class="total-value">Rp {{ number_format($payrollSlip->net_pay, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if($payrollSlip->notes)
            <div style="font-size: 10px; color: #716D7D; margin-top: 8px;">
                <em>*Catatan: {{ $payrollSlip->notes }}</em>
            </div>
        @endif

        <!-- Footer Stamp -->
        <table style="margin-top: 10px;">
            <tr>
                <td style="font-size: 9px; color: #9CA3AF;">
                    Dicetak secara otomatis via StudioFinance pada {{ date('d F Y H:i') }}.
                </td>
                <td class="stamp-box">
                    <div>Studio Kemang Creative Management</div>
                    <div class="stamp-mark">🐾 VERIFIED & PAID WITH LOVE</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
