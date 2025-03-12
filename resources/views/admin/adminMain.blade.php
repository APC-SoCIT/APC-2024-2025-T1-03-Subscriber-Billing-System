@include('layout.link')
@if(Session::has('loginId'))
<!DOCTYPE html>
<html lang="en">
//HasheemDitano
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header d-flex align-items-center">
        <img src="../storage/Images/dd.png" alt="Logo" class="logo" width="100px" height="50px">
            <div>
                <p class="mb-0">D&D IT NETWORKS</p>
                <p class="text-muted mb-0">I.T Network and Data Solution</p>
            </div>
        </div>

        <nav class="nav flex-column mt-4">
            <a class="nav-link {{ Route::currentRouteName() == 'admin.home' ? 'active' : '' }}" href="{{ route('admin.home') }}">Home</a>
            <a class="nav-link {{ Route::currentRouteName() == 'adminSubscription' ? 'active' : '' }}" href="{{ route('adminSubscription') }}">Subscriptions</a>
            <a class="nav-link {{ Route::currentRouteName() == 'adminUser' ? 'active' : '' }}" href="{{ route('adminUser') }}">Users</a>
        </nav>

    </div>




    <div class="content">

        <div class="topbar mb-5">
            <div></div>
            <div class="user-info">
                <img src="https://icap.columbia.edu/wp-content/uploads/noun_User_28817-2.png" alt="User Profile">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Session::get('user.firstName') }} {{ Session::get('user.lastName') }}

                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('adminLogout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>


        @yield('adminSubscriptions')
        @yield('adminUsers')
        @yield('adminUserList')
    </div>

</body>
@endif

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #F0F4FF;
    }

    .nav-link.active {
        color: #0AB1C5 !important;
        font-weight: bold;
        border-left: 4px solid #0AB1C5;
        background-color: rgba(10, 177, 197, 0.1);
        padding-left: 10px;

    }




    .sidebar {
        width: 250px;
        height: 100vh;
        background: white;
        position: fixed;
        padding-top: 20px;
        border-radius: 0 15px 15px 0;
    }

    .sidebar a {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
    }

    .sidebar a:hover {
        background: #e9ecef;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
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
</style>

</html>
