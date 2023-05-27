<?php
/**
 * Header of the application
 * @author ThinkxSoftware
 * **/ 
include_once '../templates/SecurePageHeader.php';
/***
 * reusable components to inject code into the template
 * */ 
include_once '../templates/Components.php';

if(!can('create-users')) header('Location:../Errors/unAuthorized.php'); 

breadCrumbs(['title' => 'Add Users', 'sub_title' => 'Add Users', 'previous' => 'Users', 'previous_action' => './index.php']);


startContent();

// code here






$allroles = $accessController->getRoles()

?>

<div class="row mb-2">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary  p-3">

            <div class="card-body">
                <p class="login-box-msg">Register a new user</p>
                <form method="POST" action="./store.php">

                    <?php

                        Input(array(
                         "type" => "text",
                         "name" => "name",  
                         "required" => true,
                         "label" => "Name",
                         "class" => "form-control", 
                         'placeholder' => 'Enter User Name'
                        ));

                        Input(array(
                            "type" => "email",
                            "name" => "email",  
                            "required" => true,
                            "label" => "Email",
                            "class" => "form-control" ,
                            'placeholder' => 'Enter User Email'
                        ));

                        Input(array(
                            "type" => "text",
                            "name" => "phone",  
                            "required" => true,
                            "label" => "Phone Number",
                            "class" => "form-control" ,
                            'placeholder' => 'Enter Phone Number for example +256752665888'

                           ));


                    ?>
                    <!--gender-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">select gender</label>
                            <select id="my-select" class="form-control" name="gender">
                                <option selected disabled>select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                    </div>
                    <!--gender-->

                    <!--role-->
                    <div class="form-group">
                        <label for="my-select">Select Role</label>
                        <select id="my-select" class="form-control" name="roles">
                            <option selected disabled>select role</option>
                            <?php
                            foreach ($allroles as $role) {
                            ?>
                                <option value="<?= $role["id"] ?>"><?= $role["name"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!--role-->

                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-75 style_btn" name="addUser">Register New User</button>
                    </div>
                    <!-- /.col -->
            </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
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