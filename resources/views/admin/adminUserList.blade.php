@extends('admin.adminMain')

@section('adminUserList')
<div class="container mt-4">
    <h4>Billing History</h4>

    <div class="card shadow-sm p-3 mt-3 d-flex flex-row align-items-center" style="border-radius: 10px;">
        <div class="flex-grow-1">
            <p class="mb-1"><strong>User Name:</strong> {{ $user->firstName ?? 'Not found' }} {{ $user->lastName ?? '' }}</p>
            <p class="mb-1"><strong>Account Number:</strong> {{ $user->id ?? 'Not found' }}</p>
            <p class="mb-1"><strong>Selected Plan:</strong> {{ $latestPayment->subscription->subscription ?? 'No Selected Plan'}}</p>

            @if (!empty($latestPayment) && !empty($latestPayment->subscription))

            <form action="{{ route('cancelSubscription') }}" method="post">
                @csrf
                <input type="hidden" name="userId" value="{{ $user->id }}">
                <input type="hidden" name="cancel" value="{{ $latestPayment->subscriptions_id }}">
                <button type="submit">CANCEL SUBSCRIPTION</button>
            </form>
            @endif
        </div>

        <div>
            <img src="https://icap.columbia.edu/wp-content/uploads/noun_User_28817-2.png" alt="User Image" class="rounded" style="width: 60px; height: 60px;">
        </div>
    </div>

    <h5 class="mt-4">Payment History</h5>
    <div id="statementTable">
        <div class="usersTableBox mt-5">
            <table class="usersTable" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>PLAN</th>
                        <th>BILLING</th>
                        <th>PAYMENTS</th>
                        <th>BALANCE</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($payments->isNotEmpty())
                    @foreach ($payments as $payment)
                    @php
                    $billing = $payment->subscription->price ?? 0;
                    $paymentAmount = $payment->amount ?? 0;
                    $paymentBalance = $billing - $paymentAmount;
                    $isDeleted = $payment->deleted_at !== null;
                    @endphp
                    <tr style="{{ $isDeleted ? 'color: red;' : '' }}">
                        <td>{{ $payment->created_at ? $payment->created_at->format('Y-m-d') : 'Not found' }}</td>
                        <td>{{ $payment->subscription->subscription ?? 'Not found' }}</td>
                        <td>&#8369; {{ number_format($billing, 2) }}</td>
                        <td>&#8369; {{ number_format($paymentAmount, 2) }}</td>
                        <td>&#8369; {{ number_format($paymentBalance, 2) }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" class="text-center">No payment history found.</td>
                    </tr>
                    @endif
                </tbody>

                <tfoot>
                    <tr>
                        <td></td>
                        <td><strong>TOTAL</strong></td>
                        <td><strong>&#8369; {{ isset($totalBilling) ? number_format($totalBilling, 2) : '0.00' }}</strong></td>
                        <td><strong>&#8369; {{ isset($totalPayments) ? number_format($totalPayments, 2) : '0.00' }}</strong></td>
                        <td><strong>&#8369; {{ isset($balance) ? number_format($balance, 2) : '0.00' }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    h4 {
        color: #38517E;
        font-weight: bold;
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
</style>
@endsection