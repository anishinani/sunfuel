<?php
/***
 * Session file
 * session management script in the application 
 * usage included in all project modules
 * @author ThinkxSoftware
 * ***/ 

session_start();

$fallback = "Location:/creditpluswebapp/index.php";

if(empty($_SESSION) || !isset($_SESSION['user']) || (isset($_SESSION['roles']) && empty($_SESSION['roles']))){

    $_SESSION = array();
    session_destroy();

    header($fallback);
}

/***
 * Enforce a more strict check such as session expiry
 * Limit the sessions to not more than a day
 * ***/ 
if(isset($_SESSION['s_time'])){

  $cs_time =  strtotime($_SESSION['s_time'].'+ 1 Day');

  if(strtotime('now') > $cs_time) {
      $_SESSION = array();
      session_destroy();
      header($fallback);

  }
}

/***
 * You can cash or store session date to a session file or cache DB like sqlite or Redis and compare
 * the session  data if a variable changes automatically flag a brute-fore or throw some exception
 * 
 * **/ 

 function can(string $permission):bool{
   return (in_array($permission , $_SESSION['permissions']));
 }

 function notAuthorizedResponse(){
    echo json_encode(['message' => 'not authorized' , "data" => [] ]);
 }