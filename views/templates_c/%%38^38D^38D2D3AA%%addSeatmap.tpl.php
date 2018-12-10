<?php /* Smarty version 2.6.31, created on 2018-12-08 15:26:02
         compiled from addSeatmap.tpl */ ?>
<html>
<head>
    <!-- Boostrap core including -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>


    <title>Seat-map management</title>

    <?php echo '
        <style>
            .avatar-upload {
                position: relative;
                max-width: 205px;
                margin: 50px auto;
            }
            .avatar-upload .avatar-edit {
                position: absolute;
                right: 12px;
                z-index: 1;
                top: 10px;
            }
            .avatar-upload .avatar-edit input {
                display: none;
            }
            .avatar-upload .avatar-edit input + label {
                display: inline-block;
                width: 34px;
                height: 34px;
                margin-bottom: 0;
                border-radius: 100%;
                background: #FFFFFF;
                border: 1px solid transparent;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                cursor: pointer;
                font-weight: normal;
                transition: all 0.2s ease-in-out;
            }
            .avatar-upload .avatar-edit input + label:hover {
                background: #f1f1f1;
                border-color: #d6d6d6;
            }
            .avatar-upload .avatar-edit input + label:after {
                content: "\\f040";
                font-family: \'FontAwesome\';
                color: #757575;
                position: absolute;
                top: 10px;
                left: 0;
                right: 0;
                text-align: center;
                margin: auto;
            }
            .avatar-upload .avatar-preview {
                width: 192px;
                height: 192px;
                position: relative;
                border-radius: 100%;
                border: 6px solid #F8F8F8;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
            }
            .avatar-upload .avatar-preview > div {
                width: 100%;
                height: 100%;
                border-radius: 100%;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }
        </style>
    '; ?>


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
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            Hi! <?php echo $this->_tpl_vars['username']; ?>

        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<!-- Site position -->
<span class="badge badge-light">
        <a class="link" href="dashboard.php">Dashboard</a> / Add Seatmap
    </span>

<div class="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5 m-auto">
                <h1 class="display-4 text-center">Add Seatmap</h1>
                <p class="lead text-center">This function for administrator</p>

                                <?php if (isset ( $this->_tpl_vars['error'] )): ?>
                    <?php $_from = $this->_tpl_vars['error']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['foo']):
?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $this->_tpl_vars['foo']; ?>

                        </div>
                    <?php endforeach; endif; unset($_from); ?>

                <?php endif; ?>

                                <?php if (isset ( $this->_tpl_vars['message'] ) && $this->_tpl_vars['success'] == false): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->_tpl_vars['message']; ?>

                    </div>
                <?php elseif (isset ( $this->_tpl_vars['message'] ) && $this->_tpl_vars['success'] == true): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $this->_tpl_vars['message']; ?>

                    </div>
                <?php endif; ?>

                <form action="../controllers/addSeatmap.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" placeholder="Seatmap name" name="seatmapName"
                             value="<?php echo $this->_tpl_vars['seatmapName']; ?>
"  />
                    </div>
                    <div class="form-group">
                        <div class="alert alert-info">
                            <strong>Note!</strong> Select your seatmap image.
                        </div>
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type='file' name="fileToUpload" id="fileToUpload" accept=".png, .jpg, .jpeg .gif" />
                                <label for="fileToUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview" style="background-image: url(../images/default.png);">
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="submit" class="btn btn-info btn-block mt-4"/>
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
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="../libs/custom/css/mainCustom.css">
<script src="../libs/custom/js/mainCustom.js"></script>

</body>
</html>