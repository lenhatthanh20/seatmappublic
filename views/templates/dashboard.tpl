<html>
<head>

    <link rel="shortcut icon" href="/seatMap/images/default.png">

    <!-- Boostrap core including -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

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
            <ul class="navbar-nav ml-auto">
                <li class="nav-item  mr-2">
                    <button id="saveToSeatmap" type="button" class="btn btn-block btn-outline-success">Save</button>
                </li>
                <li class="nav-item">
                    <button id="cancelSaveSeatmap"type="button" class="btn btn-block btn-outline-danger">Cancel</button>
                </li>
            </ul>
        </div>
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
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            Hi! {$username}
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<!-- Site position -->
<nav id="breadcrumb-fixed" aria-label="breadcrumb" style="margin-top:-6px;">
    <ol id="breadcrumb-fixed-ol" class="breadcrumb arr-right bg-dark ">
        <li class="breadcrumb-item "><a href="/seatmap/controllers/index.php" class="text-light"><i class="fa fa-home"></i> Homepage</a></li>
        <li class="breadcrumb-item text-light active" aria-current="page">Dashboard</li>
    </ol>
</nav>

{if isset($success) && isset($messsage)}
    <script>alert($messsage)</script>
{/if}

<div class="login" style="min-height: 1000px; margin-top:130px;">
        <div class="row" style="max-width: 100%">
            <div id="sidebarCustom" class="col-2 fbox" style="left:0px; position: inherit; margin-top:15px;"">
                <div class="card text-white bg-dark mb-3" style="max-height: 83%;overflow-y: auto;height: auto;margin-top: -15px;border-radius:0px;">
                    <div class="card-header">
                        <!-- <h5 class="card-title">Members have no seats</h5> -->
                        <!-- Search form -->
                        <div class="input-group">
                            <input id="searchAndFilter" type="text" class="form-control" placeholder="Profile">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <ul class="users-list clearfix">
                            {foreach from=$arrayAllProfile item=foo}
                                {if !(isset($foo[5]) && isset($foo[6]))}
                                    <div class="drapProfile">
                                        <li class="drapProfile" data-id="{$foo[0]}" data-path="{$foo[3]}" data-name="{$foo[1]}">
                                            <form method="post" action="deleteUser.php">
                                                <input type="hidden" name="id" value="{$foo[0]}">
                                                <input type="hidden" name="path" value="{$foo[3]}">
                                                <button type="submit" data-user-name="{$foo[1]}" class="removeUser">&times;</button>
                                            </form>

                                            <img src="{$foo[3]}" height="90px" width="90px" Image">
                                            <a href="updateUser.php?id={$foo[0]}"><p class="users-list-name">{$foo[1]}</p></a>
                                        </li>
                                    </div>
                                {/if}

                            {/foreach}
                        </ul>
                        <!-- /.users-list -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!--/.card -->
            </div>

            <div id="seatmapCustom" class="col-10">

                    <div id="backgroundImage"  data-seatmapID="{$arrayAllSeatmap[0][0]}" data-seatmapPath="{$arrayAllSeatmap[0][1]}" style="background-image: url('{$arrayAllSeatmap[0][1]}');">
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

<link rel="stylesheet" href="../libs/custom/css/mainCustom.css">
<link rel="stylesheet" href="/seatmap/libs/custom/css/responsive.css">
<script src="../libs/custom/js/mainCustom.js"></script>
</body>
</html>