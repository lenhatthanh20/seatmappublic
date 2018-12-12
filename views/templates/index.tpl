<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9" />

    <link rel="shortcut icon" href="/seatMap/images/default.png">

    <!-- Boostrap core including -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>



    <title>Seat-map management</title>

</head>
<body style="height: 100%;">
<!-- Navbar -->
<nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">

    <button id="showSeatmap" type="button" class="btn btn-default" style="margin-left:15px;">Show Seatmap</button>

    <div class="container">
        <a class="navbar-brand" href="/seatMap/controllers/index.php">Seat Map Management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobile-nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mobile-nav">


        </div>
    </div>

    <div class="collapse navbar-collapse" id="mobile-nav">

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/seatmap/controllers/login.php">
                    <button type="button" class="btn btn-block btn-info">Login for Admin</button>
                </a>
            </li>
        </ul>
    </div>
</nav>

{if isset($success) && isset($messsage)}
    <script>alert($messsage)</script>
{/if}

<div class="login" style="min-height: 720px; margin-top:100px;">
    <div class="row" style="max-width: 100%;">

        <div id="sidebarCustom" class="col-2 fbox" style="left:0px; position: inherit; margin-top:15px;">
            <div class="card text-white bg-dark mb-3" style="max-height: 83%;overflow-y: auto;height: auto;margin-top: -15px;border-radius:0px;">
                <div class="card-header">
                    <h5 class="card-title">Tutorial for guest and administration</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-3">
                    <h5><span class="badge badge-secondary mt-2">For guest:</span></h5>
                        - Guest can see all profiles that have seat in the map.<br>
                        - Guest can click <span class="badge badge-light">Show Seatmap</span> button to switch to another map.
                    <h5><span class="badge badge-secondary  mt-3">For admin:</span></h5>
                    - Admin can click <span class="badge badge-info">Login for Admin</span> button for login action and then can get more permission.
                    <!-- /.users-list -->
                </div>
                <!-- /.card-body -->
            </div>
            <!--/.card -->

            <div class="card text-white bg-dark mb-3" style="max-height: 83%;overflow-y: auto;height: auto;margin-top: -15px;border-radius:0px;">
                <!-- /.card-header -->
                <div class="card-body p-3">
                    <span id="please" class="badge badge-warning"> Please</span> scroll down and right to see full map!
                    <!-- /.users-list -->
                </div>
                <!-- /.card-body -->
            </div>
            <!--/.card -->
        </div>

        <div id="seatmapCustom" class="col-10">
            <div id="backgroundImage" data-seatmapID="{$arrayAllSeatmap[0][0]}" data-seatmapPath="{$arrayAllSeatmap[0][1]}" style="background-image: url('{$arrayAllSeatmap[0][1]}');">
            </div>

            <div id="listAllSeatmap" class="row">
                <!-- Seatmap image will be here -->
            </div>

        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white p-4 text-center fixed-bottom">
    Copyright &copy; 2018 Le Nhat Thanh
</footer>

<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

<link rel="stylesheet" href="/seatmap/libs/custom/css/mainCustom.css">
<link rel="stylesheet" href="/seatmap/libs/custom/css/responsive.css">
<script src="/seatmap/libs/custom/js/loadDatabase.js"></script>
</body>
</html>