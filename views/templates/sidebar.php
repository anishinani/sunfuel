<?php

$modules = $accessController->getModules();

$current_authenticated_user_permissions = $_SESSION['permissions'];

?>

<!-- start the sidebar -->

<div  class="sideBar-color">
    <aside class="main-sidebar sidebar-dark-primary elevation-4 sideBar-color">
        <a href="#" class="brand-link">
            <img src="/sunfuel/dist/img/logo.png" alt="SunShine Financial Services" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">SunShine Financial Services</span>
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/sunfuel/dist/img/logo.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        <?= $_SESSION['user']; ?>
                    </a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                    <?php
                    foreach ($modules as $module) {
                    
                        if(in_array($module['id'],$_SESSION['modules'])):
                    
                    ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon <?= $module['icon'] ?? $module['icon']  ?>"></i>
                                <p>
                                    <?= $module['name']  ?>
                                    <i class="fas fa-angle-left right"></i>

                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                foreach ($module['features'] as $feature) {
                                 if(in_array($feature['permission'] , $current_authenticated_user_permissions)):
                                    
                                    if(str_starts_with(strtolower($feature['permission']) , 'create-') ||str_starts_with(strtolower($feature['permission']),'view-')):
                                ?>
                                    
                                    <li class="nav-item">
                                        <a href="<?=$feature['action']?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p><?=$feature['name']?></p>
                                        </a>
                                    </li>

                                <?php
                                    endif;
                                endif;
                                }
                                ?>
                            </ul>
                        </li>

                    <?php
                    endif;
                    }

                    ?>
                </ul>
            </nav>
        </div>
    </aside>
</div>