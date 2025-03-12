@extends('main')

@section('home')
<div class="main-content">
    <div class="topBox">
        <div class="left-content">
            <h2>Hello, {{ Session::get('user.firstName') }} {{ Session::get('user.lastName') }}!</h2>
            <p>Thank you for choosing D&D IT Networks! Explore your account, manage your subscription, and enjoy fast, reliable internet service.</p>
        </div>
        <div class="right-content">
            <img src="storage/Images/dd.png" alt="D&D Logo">
        </div>
    </div>


    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card p-4">
                <h5 style="color: #38157E;">MY PLAN</h5>
                <h3 class="text-primary">{{ isset($payment->subscription) ? $payment->subscription->subscription : 'No subscription yet' }}

                </h3>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5 style="color: #38157E; margin-bottom:1rem;">CHANGE PLAN</h5>
                <p>Do you want to change plan?</p>
                <a href="{{route('changeSub')}}"><button class="changePlanBtn">Click here &gt;</button></a>
            </div>
        </div>
    </div>
    <div class="mt-5 mb-5" style="width: 50%; margin: 0 auto; text-align: center;">
        <h3 class="mt-5 font-bold">OUR SERVICES</h3>
        <div style="width: 50px; height: 3px; background-color: #0096c7; margin: 8px auto;"></div>
        <p>
            Provide fast, reliable & stable internet with efficient & effective repair teams and have a courteous and accommodating customer relation team.
            <br>
            Good after-sales and customer relations services where you can talk to us through group chats or Call & Text messages regarding your concerns.
        </p>
    </div>
    <div class="row">
        <div class="col-md-3 d-flex">
            <div class="card p-3 text-center h-100 w-100">
                <img src="storage/Images/service1.jpg" class="img-fluid mb-3" alt="Service 1">
                <h6>Fiber Optic Network Installation</h6>
                <p>We offer fiber optic broadband connection to the community to provide high-speed internet services to residentials and commercials.</p>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card p-3 text-center h-100 w-100">
                <img src="storage/Images/service2.jpg" class="img-fluid mb-3" alt="Service 2">
                <h6>Internet Packages</h6>
                <p>We offer a variety of affordable internet plans to meet the needs of different customers, from basic plans for individual users to more advanced plans for businesses and organizations.</p>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card p-3 text-center h-100 w-100">
                <img src="storage/Images/service3.jpeg" class="img-fluid mb-3" alt="Service 3">
                <h6>Technical Support</h6>
                <p>Our team of experienced professionals provides technical support to ensure that our networks are always running smoothly.</p>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card p-3 text-center h-100 w-100">
                <img src="storage/Images/service4.jpg" class="img-fluid mb-3" alt="Service 4">
                <h6>Customer Service</h6>
                <p>We are committed to providing excellent customer service, and our support team is available 24/7 to answer questions and troubleshoot any issues.</p>
            </div>
        </div>
    </div>

</div>
<style>
    .topBox {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 100%;
    }

    h6 {
        color: #38157E;
        font-weight: bold;
        font-size: larger;
        margin-bottom: 3rem;
    }

    .img-fluid {
        width: 100%;
        max-height: 100%;
    }

    .left-content {
        flex: 1;
    }

    .text-primary {
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .right-content img {
        width: 300px;
        height: auto;
    }

    .topBox h2 {
        color: #0AB1C5;
    }

    .userBox img {
        width: 150px;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .left-content h2 {
        color: #38157E;
    }

    .changePlanBtn {
        background-color: #0AB1C5;
        color: white;
        border-radius: 10px;
        border: none;
        width: 30%;
        padding: 5px 15px;
    }
</style>
@endsection

// Nekeisha Elfa
