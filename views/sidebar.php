<style>
    .sideBar-color {
        /* background-color:#28A745 !important; */
        margin-right: 20px !important;
    }
</style>

<?php

// $url =  $_SERVER['REQUEST_URI'];
// $bool = false;

// if (strpos('views', $url)) {
//     $bool = true;
// }

// echo $bool;
?>
<!-- Main Sidebar Container -->
<div class="sideBar-color">
    <aside class="main-sidebar sidebar-dark-primary elevation-4 sideBar-color">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="/creditpluswebapp/dist/img/logo.png" alt="credit plus" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Credit Plus</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/creditpluswebapp/dist/img/logo.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        <?php echo $_SESSION['user']; ?>
                    </a>
                </div>
            </div>


            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Manage Users
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/users/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/users/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create Users</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                Manage Roles
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/roles/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Roles</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/roles/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create Roles</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- territory -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-map"></i>
                            <p>
                                Territory
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/territories/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Territories</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/territories/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create Territories</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- territory -->


                        <!--stage user-->

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-stop"></i>
                            <p>
                                Stage
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/stage/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Stage</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/stage/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Boda Stage</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                        <!--stage user-->


                        <!--boda user-->

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-motorcycle"></i>
                            <p>
                                Boda Riders
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/bodauser/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Boda Riders</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/bodauser/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Boda Users</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                        <!--boda user-->

                        <!--fuel station-->

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-gas-pump"></i>
                            <p>
                                Manage Fuel Station
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/fuelstation/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Fuel Stations</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/fuelstation/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Fuel Sations</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                        <!--fuel station-->

                        <!--fuel station agent-->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>
                                Manage Fuel Agents
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/fuelagent/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Fuel Agents</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/fuelagent/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Fuel Agents</p>
                                </a>
                            </li>

                        </ul>

                        <!--fuel station agebt-->

                        <!--packages-->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-life-ring"></i>
                            <p>
                                Manage Packages
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/packages/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Packages</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/packages/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Packages</p>
                                </a>
                            </li>

                        </ul>

                        <!--packages-->

                        <!--loans-->

                        <!-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Manage Loans
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/loans/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Loans</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/loans/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Loans</p>
                                </a>
                            </li>

                        </ul> -->
                        <!--loan-->

                        <!--payments-->

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>
                                Manage Payments
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/payments/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Payments</p>
                                </a>
                            </li>


                        </ul>
                        <!--paymensts-->
                        <!--payments-->

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>
                                Deposits
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/deposits/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View Deposits</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/creditpluswebapp/views/deposits/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Deposits</p>
                                </a>
                            </li>


                        </ul>
                        <!--paymensts-->

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

</div>