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

startContent();

// code here


breadCrumbs(['title' => 'Assign Permissions', 'sub_title' => 'settings', 'previous' => 'Home', 'previous_action' => './index.php']);


?>

<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Register a new Role</p>
                <form method="POST" action="./store.php">
                    <div class="form-group mb-3">
                        <label for="">Role Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter role name" />
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Permissions</label>
                        <?php

                        /**
                         * module is globally available in the app
                         * **/
                        foreach ($modules as $module) {
                        ?>
                            <div class="form-check form-group">
                                <input type="checkbox" id="" class="form-check-input" name="modules[]" value="<?= $module['id'] ?>">
                                <label class="form-check-label" for="<?= $module['name'] ?>">
                                    <?= $module['name'] ?>
                                </label>
                                <ul class="ml-2">
                                    <?php

                                    foreach ($module['features'] as $permissions) {
                                    ?>
                                        <li>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="permissions[]" id="" value="<?=$permissions['id']?>">
                                                <label class="form-check-label" for="<?= $permissions['permission'] ?>">
                                                    <?= $permissions['permission'] ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php
                                    }

                                    ?>
                                </ul>
                            </div>

                        <?php
                        }
                        ?>

                    </div>
                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="style_button btn btn-primary w-75" name="addRole">Register Role</button>
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

endPage();
