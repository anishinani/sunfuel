<?php

/**
 * Creating reuseable component to make the app more dynamic
 * @author ThinkxSoftware
 * ***/

/**
 * @method Input creates an input component element
 * @param $props configurations  e.g ['type' => 'text' , 'name' => 'name'  'attributes' => 'max="10" maxLength=''']
 * */
function Input(array $props)
{

?>

  <div class="form-group">
    <label for="<?= $props['label'] ?>"> <?= ucfirst($props['label']) ?></label>
    <input type="<?= $props['type'] ?>"  placeholder="<?= $props['placeholder'] ?>"   name="<?= $props['name'] ?>" class="<?= $props['class'] ?>" <?= $props['required'] ?? "required" ?> <?= isset($props['disabled']) ?? "disabled" ?> <?= $props['autocomplete'] ?? "autocomplete" ?> <?= isset($props['attributes']) ?? $props['attributes'] ?>>
    <?php
    if (isset($_SESSION['errors'][$props['name']]))
      echo "<span class='invalid-feedback text-danger'>" . $_SESSION['errors'][$props['name']] . "</span>"
    ?>
  </div>
<?php

}

/**
 * @method notification
 * @param $props notification properties
 * */
function notification(array $props)
{
?>
  <a href="<?= $props['action'] ?>" class="dropdown-item">
    <?= $props['message'] ?>
    <span class="float-right text-muted text-sm"><?= $props['time'] ?></span>
    <div class="dropdown-divider"></div>
  </a>
<?php
}

/**
 * @method createBreadCrumbs 
 * **/
function breadCrumbs($props)
{
?>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 style="margin-left:-10px !important;"><?= $props['title'] ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $props['previous_action'] ? $props['previous_action'] : '/home' ?>"><?= $props['previous'] ?></a></li>
            <li class="breadcrumb-item active"><?= $props['sub_title'] ?></li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

<?php
}

function breadCrumbsTwo($props)
{
?>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 style="margin-left:-20px !important;"><?= $props['title'] ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $props['previous_action'] ? $props['previous_action'] : '/home' ?>"><?= $props['previous'] ?></a></li>
            <li class="breadcrumb-item active"><?= $props['sub_title'] ?></li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

<?php
}


/**
 * @method startContent
 * starts the content section
 * */

function startContent()
{
?>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    <?php
}


/**
 * @method endContent
 * ends pageContent
 * */
function endContent(){
?>
</div>
  </section>
</div>
<?php
} 

function endPage(){
  unset($_SESSION['errors']);
  ?>
</body>
</html>
<?php
}