<?php

/**
 * Header of the application
 * @author ThinkxSoftware
 * **/
include_once '../templates/SecurePageHeader.php';
/***
 * reusable components to inject code into the template
 * */

if (!can('create-package')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

include_once '../templates/Components.php';

startContent();

// code here


breadCrumbs(['title' => 'Add Package', 'sub_title' => 'Add Package', 'previous' => 'Home', 'previous_action' => './index.php']);


?>
<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Register a new package</p>
                <form method="POST" action="./store.php">


                    <div class="form-group mb-3">
                        <label for="">Packag Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter package name" />

                    </div>
                    <!--address-->
                    <div class="form-group mb-3">
                        <label for="">Package Amount</label>
                        <input type="text" name="amount" required class="form-control" placeholder="enter package amount" />
                    </div>
                    <!--address-->

                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="style_button" name="addStation">Register Package</button>
                    </div>
                    <!-- /.col -->
            </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!--form add user-->
</div>


<?php

endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

/**
 * custom page javascript
 * **/
endPage();
