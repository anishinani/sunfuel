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


breadCrumbs(['title' => 'Settings' , 'sub_title'=>'settings' , 'previous'=>'Home' , 'previous_action' => './dashboard.php']);








endContent();

/**
 * footer of the application
 * */ 
include_once '../templates/footer.php';

/**
 * custom page javascript
 * **/ 
endPage();