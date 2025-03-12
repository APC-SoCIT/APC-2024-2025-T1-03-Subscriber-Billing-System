@extends('admin.adminMain')

@section('adminUsers')
<div class="container">
    <div class="row mt-5">

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        {{-- Show validation errors --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif



        <div class="mb-5">

            <h1 style="color: #0AB1C5;">User List</h1>

        </div>

        <div class="d-flex mb-0" style="gap: 1rem;">

            <input type="text" id="searchInput" placeholder="Search User..." class="form-control mb-3">
            <button class="addUser" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="bi bi-plus-lg"></i> Add New User</button>
        </div>

        <div class="usersTableBox mt-5">
            <table class="usersTable" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Account Number</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            @if($user->user_type === 'Admin')
                            <span style="color:black; font-size:medium;">
                                {{$user->firstName}} {{$user->lastName}}
                            </span>
                            @else
                            <a href="{{ route('adminUserList', ['id' => $user->id]) }}"
                                style="text-decoration:none; color:black; font-size:medium;">
                                {{$user->firstName}} {{$user->lastName}}
                            </a>
                            @endif
                        </td>

                        <td>{{$user->id}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->mobileNo}}</td>
                        <td>
                            <!-- Edit User Modal Trigger -->
                            <a href="" data-bs-toggle="modal" data-bs-target="#editUserModal{{$user->id}}">
                                <i class="bi bi-pencil-fill"></i>
                            </a>

                            <!-- Remove User Modal Trigger -->
                            <a href="" data-bs-toggle="modal" data-bs-target="#removeUserModal{{$user->id}}">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach


                </tbody>
            </table>


        </div>

        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('addPost') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="fName" placeholder="First Name" aria-label="default input example">
                                </div>

                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="mName" placeholder="Middle Name" aria-label="default input example">
                                </div>

                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="lName" placeholder="Last Name" aria-label="default input example">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-8">
                                    <input class="form-control mb-2" type="email" name="email" placeholder="Email Address" aria-label="default input example">
                                </div>

                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="suffix" placeholder="Suffix" aria-label="default input example">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4">
                                    <input class="form-control mb-2" type="password" name="password" placeholder="Password" aria-label="default input example">
                                </div>

                                <div class="col-4">
                                    <input class="form-control mb-2" type="number" name="mobileNo" placeholder="Mobile No." aria-label="default input example">
                                </div>

                                <div class="col-4">
                                    <select class="form-select" name="gender">
                                        <option selected>Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4">
                                    <input type="date" class="form-control" name="bday" required>
                                </div>

                                <div class="col-8">
                                    <input class="form-control mb-2" type="text" name="sAddr" placeholder="Street Address" aria-label="default input example">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4">
                                    <select class="form-select" name="city">
                                        <option selected>City</option>
                                        <option value="Mandaluyong">Mandaluyong City</option>
                                        <option value="Quezon">Quezon City</option>
                                        <option value="Taguig">Taguig City</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <input class="form-control mb-2" type="number" name="postal" placeholder="Postal Code" aria-label="default input example">
                                </div>

                                <div class="col-4">
                                    <select class="form-select" name="userType">
                                        <option selected value="User">User</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <button id="submit" class="btn btn-success" type="submit"><i class="bi bi-plus"></i> Add User</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        @foreach($users as $user)
        <div class="modal fade" id="editUserModal{{$user->id}}" tabindex="-1" aria-labelledby="editUserModalLabel{{$user->id}}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('editUserPost')}}" method="post">
                            @csrf
                            <input type="hidden" name="userId" value="{{$user->id}}">

                            <div class="row mb-2">
                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="fName" value="{{$user->firstName}}" placeholder="First Name">
                                </div>
                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="mName" value="{{$user->middleName ?? ''}}" placeholder="Middle Name">
                                </div>
                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="lName" value="{{$user->lastName}}" placeholder="Last Name">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-8">
                                    <input class="form-control mb-2" type="email" name="email" value="{{$user->email}}" placeholder="Email Address">
                                </div>
                                <div class="col-4">
                                    <input class="form-control mb-2" type="text" name="suffix" placeholder="Suffix">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4">
                                    <input class="form-control mb-2" type="number" name="mobileNo" value="{{$user->mobileNo}}" placeholder="Mobile No.">
                                </div>
                                <div class="col-4">
                                    <input class="form-control mb-2" type="password" name="password"
                                        value="{{ $user->credential->password ?? 'No Password Found' }}" placeholder="Password">


                                </div>
                                <div class="col-4">
                                    <select class="form-select" name="gender">
                                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4">
                                    <input type="date" class="form-control" value="{{$user->birthday}}" name="bday" required>
                                </div>
                                <div class="col-8">
                                    <input class="form-control mb-2" type="text" name="sAddr" value="{{$user->streetAddr ?? ''}}" placeholder="Street Address">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4">
                                    <select class="form-select" name="city">
                                        <option value="Mandaluyong" {{ $user->city == 'Mandaluyong' ? 'selected' : '' }}>Mandaluyong City</option>
                                        <option value="Quezon" {{ $user->city == 'Quezon' ? 'selected' : '' }}>Quezon City</option>
                                        <option value="Taguig" {{ $user->city == 'Taguig' ? 'selected' : '' }}>Taguig City</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <input class="form-control mb-2" type="number" name="postal" value="{{$user->postal ?? ''}}" placeholder="Postal Code">
                                </div>
                                <div class="col-4">
                                    <select class="form-select" name="userType">
                                        <option value="User" {{ $user->user_type == 'User' ? 'selected' : '' }}>User</option>
                                        <option value="Admin" {{ $user->user_type == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <button class="btn btn-success" type="submit"><i class="bi bi-pencil-fill"></i> Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @foreach($users as $user)
        <div class="modal fade" id="removeUserModal{{$user->id}}" tabindex="-1" aria-labelledby="removeUserModalLabel{{$user->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Remove User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('removeUserPost')}}" method="post">
                            @csrf
                            <input type="hidden" name="userId" value="{{$user->id}}">

                            <p style="text-align: center;">Are you sure you want to remove {{$user->firstName}} {{$user->lastName}}?</p>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <button class="btn btn-danger" type="submit"><i class="bi bi-trash-fill"></i> Remove User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>


<style>
    body {
        height: 100%;
        margin: 0;
        overflow-x: hidden;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #F0F4FF;
        border-bottom: 1px solid #ddd;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        border: 1px solid black;
    }

    .usersTableBox {
        background: #F8F9FA;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: 98%;
    }

    .card:hover {
        transform: scale(1.1);
        transition: 1s ease;
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

    .usersTable thead {
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

    .active {
        background-color: lightblue;
    }

    .card {
        box-shadow: 5px 7px gray;
        margin: 10px;
    }

    .bg {
        background-color: #F0F4FF;
        min-height: 100vh;
    }

    input {
        box-shadow: 0 0 5px gray;
    }

    textarea {
        box-shadow: 0 0 5px gray;
    }

    select {
        box-shadow: 0 0 5px gray;
    }

    .addUser {
        border: none;
        border-radius: 10px;
        background-color: #0AB1C5;
        color: white;
        width: 15%;
        height: 80%;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnailBoxes = document.querySelectorAll('[id^=thumbnailBox]');

        thumbnailBoxes.forEach(thumbnailBox => {
            const productId = thumbnailBox.id.replace('thumbnailBox', '');
            const thumbnailInput = document.getElementById(`thumbnailInput${productId}`);
            const thumbnailImage = document.getElementById(`thumbnailImage${productId}`);
            const insertTextThumbnail = document.getElementById(`insertTextThumbnail${productId}`);

            // Open file input when the thumbnail box is clicked
            thumbnailBox.addEventListener('click', function() {
                thumbnailInput.click();
            });

            // Handle file input change
            thumbnailInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Update the thumbnail image's src with the selected file
                        thumbnailImage.src = e.target.result;
                        thumbnailImage.style.display = 'block';
                        insertTextThumbnail.style.display = 'none';
                    };
                    reader.readAsDataURL(file); // Convert the file to a data URL
                }
            });
        });
    });
</script>
@endsection