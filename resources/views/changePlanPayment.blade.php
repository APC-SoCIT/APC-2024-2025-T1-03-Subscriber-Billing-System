@extends('main')

@section('changePlanPayment')

@php
$subscriptionId = request()->id; // Get subscription ID from URL
@endphp
<div class="container">

    @if (Session::has('error'))
    <div class="alert alert-warning mt-5">
        {{ Session::get('error') }}
    </div>


    @endif
    <h3 style="color: #0AB1C5;">Payment</h3 style="color: #0AB1C5;">

    <div class="payment-container">

        <div class="payment-summary">
            <h5>Total amount to pay</h5>
            <div class="amount-box">
                <span class="amount">Php {{ number_format($subscription->price, 2) }}</span>
            </div>
            <p>on or before </p>
            <strong style="color: #0988A3;">{{ \Carbon\Carbon::now()->format('F d, Y') }}</strong>
            <form action="{{ route('ChangePaypal.payment') }}" method="POST">
                @csrf
                <input type="hidden" name="userId" value="{{ Session::get('user.id') }}">
                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                <input type="hidden" name="amount" value="{{ $subscription->price }}">
                <button type="submit" class="pay-now">Pay now</button>
            </form>
        </div>

        <div class="payment-method">
            <div class="payment-option mb-3">
                <input type="radio" id="paypal" name="payment_method" value="paypal" checked>
                <label for="paypal">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Paypal_2014_logo.png" alt="Gcash Logo">
                    <strong>Paypal</strong>
                </label>
            </div>
            <p>Please proceed to complete your payment</p>
        </div>

    </div>
</div>

<style>
    h5 {
        color: #1E2A38;
        margin-bottom: 50px;
    }

    .payment-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-top: 3rem;
    }

    .payment-summary {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 40%;
        height: 100%;
    }

    .payment-summary h3 {
        margin-bottom: 3rem;
    }

    .payment-method {
        width: 50%;
        border: 1px solid #0AB1C5;
        background: white;
        padding: 20px;
        border-radius: 10px;
        height: fit-content;
        box-shadow: 0px 5px 5px -3px gray;

    }

    .payment-option {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .payment-option img {
        width: 30px;
    }

    .amount-box {
        background: #F0F4FF;
        padding: 15px;
        border-radius: 8px;
        margin: 10px 0;
        text-align: center;
    }

    .amount {
        font-size: 22px;
        font-weight: bold;
        color: #2B4A6F;
    }

    .pay-now {
        background: #0AB1C5;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 50px;
        width: 100%;
        box-shadow: 0px 5px 5px -3px gray;
    }

    .pay-now:hover {
        background: #0988A3;
    }
</style>

@endsection