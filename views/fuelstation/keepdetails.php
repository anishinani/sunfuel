<?php
for ($i = 0; $i < count($county); $i++) {
?>
    <option value="<?= $county[$i]["countyCode"] ?>">
        <?= $county[$i]["countyName"] ?></option>

<?php } ?>

<?php
for ($i = 0; $i < count($subcounty); $i++) {
?>
    <option value="<?= $subcounty[$i]["subCountyCode"] ?>">
        <?= $subcounty[$i]["subCountyName"] ?></option>

<?php } ?>

<?php
for ($i = 0; $i < count($parishes); $i++) {
?>
    <option value="<?= $parishes[$i]["parishCode"] ?>">
        <?= $parishes[$i]["parishName"] ?></option>

<?php } ?>
<?php
for ($i = 0; $i < count($villages); $i++) {
?>
    <option value="<?= $villages[$i]["villageCode"] ?>">
        <?= $villages[$i]["villageName"] ?></option>

<?php } ?>