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
breadCrumbs(['title' => 'Create Module' , 'sub_title'=>'Create Module' , 'previous'=>'Settings' , 'previous_action' => './index.php']);

?>


<div class="row mb-2">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary  p-3">

            <div class="card-body">
                <p class="login-box-msg">Create New Module</p>
                <form method="POST" action="./store-module.php">

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
                            "name" => "description",  
                            "required" => true,
                            "label" => "Description",
                            "class" => "form-control" ,
                            'placeholder' => ' ',

                        ));

                        Input(array(
                            "type" => "text",
                            "name" => "icon",  
                            "required" => true,
                            "label" => "Icon",
                            "class" => "form-control", 
                            'placeholder' => 'fas fa-{icon name}'
                           ));
                        
                    ?>
                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-75 style_btn" name="addModule">Create New Module</button>
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