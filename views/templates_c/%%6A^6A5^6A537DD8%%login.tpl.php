<?php /* Smarty version 2.6.31, created on 2018-12-07 10:04:24
         compiled from login.tpl */ ?>
<html>
<head>
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
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"> Documents
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Site position -->
<span class="badge badge-light">
    <a class="link" href="../index.php">Homepage</a> / login
</span>

<!-- Login -->
<div class="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5 m-auto">
                <h1 class="display-4 text-center">Log In</h1>
                <p class="lead text-center">Sign in to administrator account</p>

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

                <form action="login.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" placeholder="Username" name="username" value="<?php echo $this->_tpl_vars['usernameInput']; ?>
"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" placeholder="Password" name="password"/>
                    </div>
                    <input type="submit" class="btn btn-info btn-block mt-4" />
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