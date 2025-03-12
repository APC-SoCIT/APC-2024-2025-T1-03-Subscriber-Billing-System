@extends('admin.adminMain')
//Hasheem Ditano
@section('adminSubscriptions')
<div class="container">

    @if (Session::has('success'))
    <div class="alert alert-success mt-5">
        {{ Session::get('success') }}
    </div>
    @endif

    @if (Session::has('error'))
    <div class="alert alert-success mt-5">
        {{ Session::get('error') }}
    </div>
    @endif

    <div class="filterable_card" data-name="dashboard">

    </div>

    <div class="filterable_card" data-name="Subscriptions">

        <div class="container">
            <div class="row mt-5">

                <div class="mb-5">

                    <h1 style="color: #0AB1C5;">Subscription's List</h1>

                </div>

                <div class="d-flex mb-0" style="gap: 1rem;">

                    <input type="text" id="searchInput" placeholder="Search Subscription..." class="form-control mb-3">
                    <button class="addUser" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal"><i class="bi bi-plus-lg"></i> Add Subscription</button>
                </div>
            </div>

            <div class="row">
                <div class="usersTableBox mt-5">
                    <table class="usersTable" cellpadding="10" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th></th>
                                <th>Price</th>
                                <th></th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $sub)
                            <tr>
                                <td>{{$sub->subscription}}</td>
                                <td></td>
                                <td>{{$sub->price}}</td>
                                <td></td>
                                <td>
                                    <a href="" data-bs-toggle="modal" data-bs-target="#editSubModal{{$sub->id}}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <a href="" data-bs-toggle="modal" data-bs-target="#removeSubModal{{$sub->id}}">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>





            <div class="modal fade" id="addSubscriptionModal" tabindex="-1" aria-labelledby="addSubscriptionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Add Subscription</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('addSubscriptionPost') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-2">

                                    <div class="col-12 mb-3">
                                        <div id="thumbnailBox">
                                            <div class="thumbnailPicture">
                                                <p id="insertTextThumbnail">Insert Thumbnail</p>
                                                <img id="thumbnailImage" src="" alt="" style="display: none; width: 100px; height: 100px;">
                                            </div>
                                            <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" style="display: none;">
                                        </div>

                                    </div>

                                    <div class="col-6">
                                        <input class="form-control mb-2" type="text" name="plan" placeholder="Subscription Plan" aria-label="default input example">

                                    </div>

                                    <div class="col-6">
                                        <input class="form-control mb-2" type="number" name="price" placeholder="Subscription Price" aria-label="default input example">

                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div id="detailsContainer">
                                            <input class="form-control mb-2" type="text" name="details[]" placeholder="Details" aria-label="Details">
                                        </div>
                                        <button type="button" class="addDetail" onclick="addDetailA()"><i class="bi bi-plus"> </i> Add New Detail</button>

                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                    <button id="submit" class="btn btn-success" type="submit">Add Subscription <i class="bi bi-plus"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach($subscriptions as $sub)
        <div class="modal fade" id="editSubModal{{$sub->id}}" tabindex="-1" aria-labelledby="editSubModalLabel{{$sub->id}}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('editSubscriptionPost')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{$sub->id}}">

                            <div class="row mb-2">
                                <div class="col-12">
                                    <div id="thumbnailBox">
                                        <div class="thumbnailPicture">
                                            <p id="insertTextThumbnail"></p>
                                            <img id="thumbnailImage" src="{{$sub->thumbnail}}" style="width: 100px; height: 100px;">
                                        </div>
                                        <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" style="display: none;">
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <input class="form-control mb-2" type="text" value="{{$sub->subscription}}" name="plan" placeholder="Subscription Plan">
                                </div>

                                <div class="col-6 mt-3">
                                    <input class="form-control mb-2" type="text" value="{{$sub->price}}" name="price" placeholder="Subscription Price">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12">
                                    <div id="detailsContainer{{$sub->id}}">
                                        @if($sub->details->isNotEmpty())
                                        @foreach($sub->details as $detail)
                                        <div class="position-relative mb-2 detail-item" id="detail-{{$detail->id}}">
                                            <input class="form-control" type="text" value="{{ $detail->details }}" name="details[]" placeholder="Details">
                                            <button type="button" class="btn-close removeDetail" aria-label="Close" onclick="removeDetail({{ $detail->id }})"
                                                style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%);"></button>
                                        </div>



                                        @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="addDetail" onclick="addDetail({{ $sub->id }})">
                                        <i class="bi bi-plus"></i> Add New Detail
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <button id="submit" class="btn btn-success" type="submit">
                                    Update Subscription <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endforeach

        @foreach($subscriptions as $sub)
        <div class="modal fade" id="removeSubModal{{$sub->id}}" tabindex="-1" aria-labelledby="removeSubModalLabel{{$sub->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Remove Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('removeSubscriptionPost')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{$sub->id}}">
                            <p style="text-align: center;">Are you sure you want to remove {{$sub->subscription}}?</p>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <button class="btn btn-danger" type="submit"><i class="bi bi-trash-fill"></i> Remove Subscription</button>
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

    .addDetail {
        float: right;
        border: none;
        color: #0AB1C5;
        background-color: transparent;
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

    .tasks {
        font-size: medium;
        padding: 10px;
        margin-top: 10px;

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


    .addUser {
        border: none;
        border-radius: 10px;
        background-color: #0AB1C5;
        color: white;
        width: 15%;
        height: 80%;
    }

    .thumbnailPicture {
        width: 100%;
        padding: 5px;
        height: 150px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px dashed #ddd;
        border-radius: 10px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }

    .thumbnailPicture img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        display: block;
    }
</style>




<script>
    const filterButtons = document.querySelectorAll(".offcanvas-body button");
    const filterableCards = document.querySelectorAll(".filterable_card");

    let lastActiveButton = null;

    const filterCards = e => {
        const button = e.target.closest('button');

        if (button) {
            if (lastActiveButton && lastActiveButton !== button) {
                lastActiveButton.classList.remove("active");
                void lastActiveButton.offsetWidth;
            }

            button.classList.add("active");
            lastActiveButton = button;

            filterableCards.forEach(card => {
                if (card.dataset.name === button.dataset.name || button.dataset.name === "all") {
                    card.classList.remove("d-none");
                } else {
                    card.classList.add("d-none");
                }
            });
        }
    };

    filterButtons.forEach(button => button.addEventListener("click", filterCards));

    function addDetailA() {
        let newInput = document.createElement("input");
        newInput.className = "form-control mb-2";
        newInput.type = "text";
        newInput.name = "details[]";
        newInput.placeholder = "Details";
        newInput.ariaLabel = "Details";

        document.getElementById("detailsContainer").appendChild(newInput);
    }

    function addDetail(subId) {
        let detailsContainer = document.getElementById("detailsContainer" + subId);

        let newInput = document.createElement("input");
        newInput.type = "text";
        newInput.className = "form-control mb-2";
        newInput.name = "details[]";
        newInput.placeholder = "Details";

        detailsContainer.appendChild(newInput);
    }


    document.addEventListener('DOMContentLoaded', function() {
        const thumbnailBoxes = document.querySelectorAll('[id^=thumbnailBox]');

        thumbnailBoxes.forEach(thumbnailBox => {
            const productId = thumbnailBox.id.replace('thumbnailBox', '');
            const thumbnailInput = document.getElementById(`thumbnailInput${productId}`);
            const thumbnailImage = document.getElementById(`thumbnailImage${productId}`);
            const insertTextThumbnail = document.getElementById(`insertTextThumbnail${productId}`);

            thumbnailBox.addEventListener('click', function() {
                thumbnailInput.click();
            });

            thumbnailInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        thumbnailImage.src = e.target.result;
                        thumbnailImage.style.display = 'block';
                        insertTextThumbnail.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    });

    function removeDetail(detailId) {
        fetch("{{ route('editSubscriptionDetail') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    detail_id: detailId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("detail-" + detailId).remove();
                }
            })
            .catch(error => console.error("Error:", error));
    }
</script>
@endsection
