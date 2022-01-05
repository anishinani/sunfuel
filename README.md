Status for the different users in the system
0-means not activated
1-means activated
2-pending payment
3-suspended

  
serverUrl:http://appdev.creditplus.ug/
localhostUrl:http://localhost
============================
Login API:{envUrl}/creditpluswebapp/api/login.php
method:post
Required fields:login , email, password
dataType:form-data
OnSuccess :
  Sample Success data
[{"name":"katende
nicholas"},{"email":"katznicho@gmail.com"},{"gender":"male"},{"phoneNumber":"0759983853"},{"roleId":1},{"dp":null}]

On Failure
  If fields are empty:All fields are Required
  if wrong data is provided:Inavlid Credentials
  if wrong email address:Invalid Credentials

===========================