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

<div class="container" style="margin-top: 150px;">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Not Found</h2>
                <div class="error-details">
                    Sorry, an error has occured, Requested page not found!
                </div>
                <div class="error-actions">
                    <a href="/seatmap/controllers/index.php" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Take Me Home </a><a href="/seatmap/controllers/index.php" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> Contact Support </a>
                </div>
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