@extends('main')
//Hasheem Ditano

@section('accPage')


@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<div class="topBox">
    <div class="left-content">
        <h2>Hello, {{ Session::get('user.firstName') }} {{ Session::get('user.lastName') }}!</h2>
        <p>Thank you for choosing D&D IT Networks! Explore your account, manage your subscription, and enjoy fast, reliable internet service.</p>
    </div>
    <div class="right-content">
        <img src="storage/Images/dd.png" alt="D&D Logo">
    </div>
</div>

<div class="bottomBox">
    <form action="{{ route('accountPagePost') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="userId" value="{{ Session::get('user.id') }}" hidden>
        <div class="row mb-2">
            <div class="col-4">
                <input class="form-control mb-2" type="text" name="fName" value="{{ Session::get('user.firstName') }}" placeholder="First Name" disabled>
            </div>

            <div class="col-4">
                <input class="form-control mb-2" type="text" name="mName" value="{{ Session::get('user.middleName') }}" placeholder="Middle Name" disabled>
            </div>

            <div class="col-4">
                <input class="form-control mb-2" type="text" name="lName" value="{{ Session::get('user.lastName') }}" placeholder="Last Name" disabled>
            </div>
        </div>


        <div class="row mb-2">
            <div class="col-8">
                <input class="form-control mb-2" type="email" name="email" value="{{ Session::get('user.email') }}" placeholder="Email Address">
            </div>

            <div class="col-4">
                <input class="form-control mb-2" type="text" name="suffix" value="{{ Session::get('user.suffix') }}" placeholder="Suffix" disabled>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-4">
                <!-- Show the hashed password -->
                <input class="form-control mb-2" type="password" name="password"
                    value="{{ Session::get('user.credential.password') ?? 'No Password Found' }}" placeholder="Password">
            </div>

            <div class="col-4">
                <input class="form-control mb-2" type="number" name="mobileNo" value="{{ Session::get('user.mobileNo') }}" placeholder="Mobile No.">
            </div>

            <div class="col-4">
                <select class="form-select" name="gender" disabled>
                    <option value="Male" {{ Session::get('user.gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ Session::get('user.gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-4">
                <input type="date" class="form-control" name="bday" value="{{ Session::get('user.birthday') }}" required>
            </div>

            <div class="col-8">
                <input class="form-control mb-2" type="text" name="sAddr" value="{{ Session::get('user.streetAddr') }}" placeholder="Street Address">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-4">
                <select class="form-select" name="city">
                    <option value="Mandaluyong" {{ Session::get('user.city') == 'Mandaluyong' ? 'selected' : '' }}>Mandaluyong City</option>
                    <option value="Quezon" {{ Session::get('user.city') == 'Quezon' ? 'selected' : '' }}>Quezon City</option>
                    <option value="Taguig" {{ Session::get('user.city') == 'Taguig' ? 'selected' : '' }}>Taguig City</option>
                </select>
            </div>

            <div class="col-4">
                <input class="form-control mb-2" type="number" name="postal" value="{{ Session::get('user.postal') }}" placeholder="Postal Code">
            </div>

        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
            <button id="submit" class="btn btn-success" type="submit"><i class="bi bi-plus"></i> Update Information</button>
        </div>

    </form>

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

    .bottomBox {
        width: 100%;
        height: fit-content;
        background-color: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 4rem;
    }

    .bottomBox input,
    select {

        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>

@endsection
