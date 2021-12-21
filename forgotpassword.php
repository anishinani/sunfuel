<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Forgot Password </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        .login-page {
            position: relative !important;
        }

        .image__remove {
            position: absolute !important;
            right: 30px !important;
            top: 10px !important;
            cursor: pointer;
        }

        #removeAlert {
            margin-top: 10px !important;
        }
    </style>
</head>

<body class="hold-transition login-page">


    <?php if (isset($_SESSION['requestPasswordError'])) { ?>
        <div class="alert alert-danger m-4" id="removeAlert">
            <p><?= $_SESSION['requestPasswordError']; ?></p>


        </div>

    <?php }
    unset($_SESSION['requestPasswordError']);

    ?>
    <div class="login-box">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Credit</b>Plus</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                <p class="login-box-msg">
                    <?php if (isset($_SESSION['requestPasswordError'])) { ?>
                <div class="alert alert-danger m-4" id="removeAlert">
                    <p class="login-box-msg"><?= $_SESSION['requestPasswordError']; ?></p>


                </div>

            <?php }
                    unset($_SESSION['requestPasswordError']);

            ?>
            <?php if (isset($_SESSION['requestPasswordSuccess'])) { ?>
                <div class="alert alert-success m-4" id="removeAlert">
                    <p class="login-box-msg"><?= $_SESSION['requestPasswordSuccess']; ?></p>


                </div>

            <?php }
            unset($_SESSION['requestPasswordSuccess']);

            ?>
            </p>
            <form action="/creditpluswebapp/views/auth/requestnewpassword.php" method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" name="request" class="btn btn-primary btn-block">Request new password</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="./index.php">Login</a>
            </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!--hide alert--->
    <script type="text/javascript">
        $(function() {
            $('.image__remove').click(function() {
                //alert('clicked')
                // $("#content-wrap").addClass('platform');
                $("#removeAlert").addClass('platform');

            })
        })
    </script>
    <!--hide alert-->
</body>

</html>