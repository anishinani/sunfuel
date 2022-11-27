<?php
session_start();
include_once("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
//get email
if (isset($_GET['token'])) {
    $checkToken = $_GET['token'];
    $token = $dbAccess->select("users", ["setPassword", "email", "adminId"], ["setPassword" => $checkToken])[0];


    if (count($token)) {
        $useremail = $token['email'];
        $id =  $token["adminId"];
        //die($useremail);
    } else {
        header("Location:/creditpluswebapp/index.php");
    }
} else {
    header("Location:/creditpluswebapp/index.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/creditpluswebapp/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <!-- error part -->
        <?php if (isset($_SESSION['errors'])) { ?>

            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    <?php
                    foreach ($_SESSION['errors'] as $key => $value) {
                        echo "<li>" . $value . "</li>";
                    }
                    ?>
                </ul>
            </div>

            <!--error part-->
        <?php }

        unset($_SESSION['errors']);
        ?>
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>CREDIT </b>PLUS</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">set password to start your session</p>

                <form action="loginUser.php" method="post">

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" id="password1" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-eye" id="here1" style="cursor: pointer"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="confirm" id="password" placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-eye" id="here" style="cursor: pointer"></span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="email" value="<?= $useremail; ?>" />
                    <input type="hidden" name="id" value="<?= $id; ?>" />
                    <input type="hidden" name="token" value="<?= $checkToken; ?>" />
                    <div class="row">

                        <!-- /.col -->
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block" name="setPassword">Set Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>



            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="/creditpluswebapp/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/creditpluswebapp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/creditpluswebapp/dist/js/adminlte.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#here').click(function() {
                const type = $('#password').attr('type');

                if (type == 'password') {
                    $("password").removeAttr("type");
                    $('#password').attr("type", 'text');
                    $("#here").removeClass('fas fa-eye-slash');
                    $("#here").addClass("fas fa-eye")

                } else {
                    $("password").removeAttr("type");
                    $('#password').attr("type", 'password');
                    $("#here").removeClass("fas fa-eye")
                    $("#here").addClass('fas fa-eye-slash');


                }


            })
        })
        //password eye
        $(function() {
            $('#here1').click(function() {
                const type = $('#password1').attr('type');

                if (type == 'password1') {
                    $("password1").removeAttr("type");
                    $('#password1').attr("type", 'text');
                    $("#here1").removeClass('fas fa-eye-slash');
                    $("#here1").addClass("fas fa-eye")

                } else {
                    $("password1").removeAttr("type");
                    $('#password1').attr("type", 'password1');
                    $("#here1").removeClass("fas fa-eye")
                    $("#here1").addClass('fas fa-eye-slash');


                }


            })
        })
        //password eye
    </script>
</body>

</html>

<!-- 
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -->