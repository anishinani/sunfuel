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


breadCrumbs(['title' => 'Create Feature', 'sub_title' => 'settings', 'previous' => 'Settings', 'previous_action' => './index.php']);

?>

<div class="row mb-2 py-3">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary  p-3">

            <div class="card-body">
                <p class="login-box-msg">Create New Feature</p>
                <form method="POST" action="./store-feature.php">

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
                        "type" => "text",
                        "name" => "permission",
                        "required" => true,
                        "label" => "Permission",
                        "class" => "form-control",
                        'placeholder' => '{action}-{module}',

                    ));

                    Input(array(
                        "type" => "text",
                        "name" => "action",
                        "required" => true,
                        "label" => "Action Script",
                        "class" => "form-control",
                        'placeholder' => '../view/index.php'
                    ));

                    ?>
                    <div class="form-group">
                        <label for="module">Module</label>
                        <select name="module_id" id="" class="form-control custom-select">
                            <?php foreach($modules as $module):?>
                                <option value="<?=$module['id']?>"><?=$module['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-75 style_btn" name="createFeature">Create New Feature</button>
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
