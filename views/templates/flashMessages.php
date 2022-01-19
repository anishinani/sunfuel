<?php

/****
 * Flash messages
 * one time messages in on the current request response 
 * **/

if (isset($_SESSION['success'])) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <p><strong>Success</strong> <?= $_SESSION['success'] ?> </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
    unset($_SESSION['success']);
}
if (isset($_SESSION['info'])) {
?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <p><strong>Info</strong> <?= $_SESSION['info'] ?> </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
    unset($_SESSION['info']);
}
if (isset($_SESSION['warning'])) {
?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <p><strong>Warning!</strong> <?= $_SESSION['warning'] ?> </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
    unset($_SESSION['warning']);
}
if (isset($_SESSION['error'])) {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <p><strong>Error Occurred</strong> <?= $_SESSION['error'] ?> </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php

    unset($_SESSION['error']);
}
