@extends('main')
//Hasheem Ditano
@section('account')

<h2>Account Statement</h2>

<div class="balance-section">
    <div class="card">
        <h3>&#8369; {{ number_format($balance, 2) }}</h3>
        <p>Balance as of {{ date('F d, Y') }}</p>
        <hr>
        @if($balance > 0)
        <a href="#">Pay now &gt;</a>
        @else
        <p class="text-muted">No outstanding balance</p>
        @endif
    </div>


    <div class="card">
        @if($balance > 0)
        <h3>&#8369; {{ number_format($balance, 2) }}</h3>
        <p>Due Date: {{ $nextDueDate->format('F d, Y') }}</p>
        <hr>
        <p class="text-muted">Amount required for the next billing</p>
        @else
        <h3 class="text-success">&#8369; {{ number_format($subscriptionPrice, 2) }}</h3>
        <p>Next payment due: {{ $nextDueDate->format('F d, Y') }}</p>
        <hr>
        <a href="{{ route('advancePayment', ['id' => $subscriptionId]) }}">Pay now &gt;</a>
        @endif
    </div>






</div>


<button class="download-btn" onclick="printStatement()">Download Statement <i class="bi bi-download"></i></button>

<div id="statementTable">
    <div class="usersTableBox mt-5">
        <table class="usersTable" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>DATE</th>
                    <th>REFERENCE NUMBER</th>
                    <th>PLAN</th>
                    <th>BILLING</th>
                    <th>PAYMENTS</th>
                    <th>BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                @php
                $billing = $payment->subscription->price ?? 0;
                $paymentAmount = $payment->amount;
                $paymentBalance = $billing - $paymentAmount;
                @endphp
                <tr>
                    <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                    <td>{{ $payment->reference_number }}</td>
                    <td>{{ $payment->subscription->subscription }}</td>
                    <td>&#8369; {{ number_format($billing, 2) }}</td>
                    <td>&#8369; {{ number_format($paymentAmount, 2) }}</td>
                    <td>&#8369; {{ number_format($paymentBalance, 2) }}</td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>TOTAL</strong></td>
                    <td><strong>&#8369; {{ number_format($totalBilling, 2) }}</strong></td>
                    <td><strong>&#8369; {{ number_format($totalPayments, 2) }}</strong></td>
                    <td><strong>&#8369; {{ number_format($balance, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>




<style>
    h2 {
        color: #38517E;
        margin-bottom: 20px;
    }

    .download-btn {
        background-color: #f5f5f5;
        border: 2px solid #38517E;
        color: #38517E;
        font-weight: bold;
        float: right;
        margin-bottom: 1rem;
        padding: 5px 15px;

        border-radius: 10px;
    }

    .balance-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .card {
        background: #ffffff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 48%;
    }

    .card h3 {
        margin: 10px 0;
        color: #38517E;
    }

    .usersTable {
        width: 100%;
        text-align: left;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        background: white;
    }

    tfoot {
        background-color: #0AB1C5;
        color: white;
    }

    .usersTable thead tr th {
        padding: 12px;
        font-weight: bold;
    }

    .usersTable tbody tr td {
        padding: 12px;
        border-bottom: 1px solid #E0E0E0;
    }

    .usersTable tbody tr:last-child td {
        border-bottom: none;
    }

    .usersTable tbody tr:hover {
        background-color: #f5f5f5;

    }

    .usersTable td a {
        text-decoration: none;
        color: #0AB1C5;
        margin: 0 5px;
        font-size: 18px;
    }

    .usersTable td a:hover {
        color: #087F94;
    }

    .usersTable thead tr th:first-child {
        border-top-left-radius: 10px;
    }

    .usersTable thead tr th:last-child {
        border-top-right-radius: 10px;
    }

    .usersTable tbody tr:last-child td:first-child {
        border-bottom-left-radius: 10px;
    }

    .usersTable tbody tr:last-child td:last-child {
        border-bottom-right-radius: 10px;
    }



    .usersTable i {
        color: #0AB1C5;
        font-size: large;
    }

    .balance-section a {
        text-decoration: none;
        color: #0AB1C5;
    }
</style>

<script>
    function printStatement() {
        let printContent = document.getElementById('statementTable').innerHTML;
        let printWindow = window.open('', '_blank');

        printWindow.document.write(`
            <html>
            <head>
                <title>Account Statement</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid black; padding: 8px; text-align: center; }
                    th { background-color: #f2f2f2; }
                    h2 { text-align: center; }
                </style>
            </head>
            <body>
                <h2>Account Statement</h2>
                <p>Date: ${new Date().toLocaleDateString()}</p>
                <div>${printContent}</div>
                <script>
                    window.print();
                    setTimeout(() => window.close(), 100);
                <\/script>
            </body>
            </html>
        `);

        printWindow.document.close();
    }
</script>

@endsection
