@extends('main')
//Hasheem Ditano
@section('changeSubs')



<div>

    <h3 style="color: #0AB1C5;">Subscription</h3>
</div>

<div class="row mt-5">
    @foreach($subscriptions as $sub)
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 custom-column">
        @if($payment && $payment->subscriptions_id == $sub->id)
        <div class="card" style="max-width: 18rem; max-height: 25rem; background-color: #f8f9fa;">
            <div class="container">
                <div class="card-body">
                    <img src="{{ asset($sub['thumbnail']) }}" class="card-img-top mb-3" alt="..." style="max-width: 100%; max-height: 15rem;">
                    <h5 class="card-title" style="font-weight: bold;">{{ $sub->subscription }}</h5>
                    <large>Php {{ $sub->price }}</large><br>
                    <small style="color: #0AB1C5;">per month</small>
                    <ul class="mt-3">
                        @foreach($sub->details as $detail)
                        <li>{{ $detail->details }}</li>
                        @endforeach
                    </ul>
                    <p class="text-center text-danger mt-2"><strong>This is your current plan</strong></p>
                </div>
            </div>
        </div>
        @else
        <!-- Other Plans: Clickable -->
        <div class="card" style="max-width: 18rem; max-height: 25rem;">
            <a href="{{ route('changePlanPayment', ['id' => $sub->id]) }}" class="card-link" style="text-decoration: none; color: inherit;">
                <div class="container">
                    <div class="card-body">
                        <img src="{{ asset($sub['thumbnail']) }}" class="card-img-top mb-3" alt="..." style="max-width: 100%; max-height: 15rem;">
                        <h5 class="card-title" style="font-weight: bold;">{{ $sub->subscription }}</h5>
                        <large>Php {{ $sub->price }}</large><br>
                        <small style="color: #0AB1C5;">per month</small>
                        <ul class="mt-3">
                            @foreach($sub->details as $detail)
                            <li>{{ $detail->details }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>
    @endforeach

</div>



<style>
    .imgSub {
        width: 150px;
        height: 150px;
    }

    a {
        text-decoration: none;
    }

    @media (min-width: 992px) {


        .custom-column {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    .card:hover {
        border: 1px solid #0AB1C5;
    }

    .active {
        color: #0AB1C5;
    }
</style>
@endsection
