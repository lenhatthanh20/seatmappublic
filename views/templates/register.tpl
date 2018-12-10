<html>
<head>
    <link rel="shortcut icon" href="/seatMap/images/default.png">
    <!-- Boostrap core including -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <title>Seat-map management</title>

</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-4">

    <!-- Back Link to DashBoard.php -->
    <a class="nav-link" href="javascript:history.back()">
        <button type="button" class="btn btn-default">Go Back</button>
    </a>

    <div class="container">
        <a class="navbar-brand" href="/seatMap/index.php">Seat Map Management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobile-nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mobile-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="register.php">
                        <button type="button" class="btn btn-block btn-info">Add User</button>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user.php">
                        <button type="button" class="btn btn-block btn-info">Add Profile</button>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="addSeatmap.php">
                        <button type="button" class="btn btn-block btn-info">Add Seatmap</button>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="btn-group">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Hi! {$username}
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<!-- Site position -->
    <span class="badge badge-light">
        <a class="link" href="dashboard.php">Dashboard</a> / register
    </span>

<!-- Body -->
<div class="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5 m-auto">
                <h1 class="display-4 text-center">Create default user account</h1>
                <p class="lead text-center">Sign up for administrator account</p>
                {* Display error *}
                {if isset($error)}
                    {foreach from=$error item=foo}
                        <div class="alert alert-danger" role="alert">
                            {$foo}
                        </div>
                    {/foreach}

                {/if}

                {* Display message *}
                {if isset($message) && $success == false}
                    <div class="alert alert-danger" role="alert">
                        {$message}
                    </div>
                {elseif isset($message) && $success == true}
                    <div class="alert alert-success" role="alert">
                        {$message}
                    </div>
                {/if}

                <form action="../controllers/register.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" placeholder="Username" name="username" value="{$usernameInput}"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" placeholder="Password" name="password"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" placeholder="Confirm password" name="password2"/>
                    </div>
                    <input type="submit" class="btn btn-block btn-info" />

                    <a class="btn btn-block btn-danger" href="dashboard.php">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white mt-5 p-4 text-center">
    Copyright &copy; 2018 Le Nhat Thanh
</footer>

<link rel="stylesheet" href="../libs/custom/css/mainCustom.css">
</body>
</html>