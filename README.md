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
Required fields:login set login:login, email set email:someEmail, password set  password:somePassord
dataType:form-data
 
Testing Data
 email:katznicho@gmail.com
 password:12345678
  
OnSuccess :
  Sample Success data
[{"name":"katende
nicholas"},{"email":"katznicho@gmail.com"},{"gender":"male"},{"phoneNumber":"0759983853"},{"roleId":1},{"dp":null}]

On Failure
  If fields are empty:All fields are Required
  if wrong data is provided:Inavlid Credentials
  if wrong email address:Invalid Credentials

===========================

==============================
Get District API :{envUrl}/creditpluswebapp/api/getdistricts.php
method:get

OnSucess:
Sample Sucess Data
[{"id":"1","districtCode":"70","districtName":"ABIM","created_at":"2022-01-01 03:09:59","updated_at":"2022-01-01
03:09:59"},{"id":"2","districtCode":"40","districtName":"ADJUMANI","created_at":"2022-01-01
03:10:00","updated_at":"2022-01-01
03:10:00"}]
===============================

==============================
Get Counties API :{envUrl}/creditpluswebapp/api/getcounties.php?districtCode=10
method:get
paramter:districtCode(data type integer)

OnSucess:
Sample Sucess Data
[{"id":"60","districtCode":"10","countyCode":"30","countyName":"BURAHYA","created_at":"2022-01-01 03:15:16","upated_at":"2022-01-01 03:15:16"},{"id":"91","districtCode":"10","countyCode":"31","countyName":"FORT PORTAL MUNICIPALITY","created_at":"2022-01-01 03:15:17","upated_at":"2022-01-01 03:15:17"}]

onFailure
Failure Data samples
if a wrong code is provided  :[{"message":"No counties available"}]
if wrong districtCode is provided:[{"message":"Please provide a district code"}]


===============================

==============================
Get Counties API :{envUrl}/creditpluswebapp/api/getsubcounties.php?districtCode=70&countyCode=140
method:get
paramter:districtCode, countyCode (both are integers)

OnSucess:
Sample Sucess Data
[{"id":"9","districtCode":"70","countyCode":"140","subCountyCode":"1","subCountyName":"ABIM","created_at":"2022-01-01 03:23:41","updated_at":"2022-01-01 03:23:41"},{"id":"10","districtCode":"70","countyCode":"140","subCountyCode":"6","subCountyName":"ABIM TOWN COUNCIL","created_at":"2022-01-01 03:23:41","updated_at":"2022-01-01 03:23:41"},{"id":"82","districtCode":"70","countyCode":"140","subCountyCode":"2","subCountyName":"ALEREK","created_at":"2022-01-01 03:23:43","updated_at":"2022-01-01 03:23:43"},{"id":"156","districtCode":"70","countyCode":"140","subCountyCode":"12","subCountyName":"ATUNGA","created_at":"2022-01-01 03:23:45","updated_at":"2022-01-01 03:23:45"},{"id":"161","districtCode":"70","countyCode":"140","subCountyCode":"7","subCountyName":"AWACH","created_at":"2022-01-01 03:23:45","updated_at":"2022-01-01 03:23:45"}]

onFailure
Failure Data samples
if a wrong codes for district and county are provided or no results found :[{"message":"No SubCounties available"}]
if wrong districtCode is provided:[{"message":"Please provide a district code"}]


===============================

==============================
Get  Parishes API :{envUrl}/creditpluswebapp/api/getparishes.php?districtCode=70&countyCode=140&subCountyCode=1
method:get
paramter:districtCode, countyCode, subCountyCode (all are integers)

OnSucess:
Sample Sucess Data
[{"id":"62","districtCode":"70","countyCode":"140","subCountyCode":"1","parishCode":"52","parishName":"ABONGEPACH","created_at":"2022-01-01 04:13:44","updated_at":"2022-01-01 04:13:44"},{"id":"195","districtCode":"70","countyCode":"140","subCountyCode":"1","parishCode":"53","parishName":"ADWAL","created_at":"2022-01-01 04:13:49","updated_at":"2022-01-01 04:13:49"}]

onFailure
Failure Data samples
if a wrong code for district, county and subcounty are provided or simply no results found  :[{"message":"No parishes available"}]
if wrong parameters passed:[{"message":"Wrong Parameters Passed"}]


===============================

==============================
Get  Villages API :{envUrl}/creditpluswebapp/api/getvillages.php?districtCode=88&countyCode=160&subCountyCode=7&parishCode=36
method:get
paramter:districtCode, countyCode, subCountyCode, parishCode (all are integers)

OnSucess:
Sample Sucess Data
[{"id":"3902","districtCode":"88","countyCode":"160","subCountyCode":"7","parishCode":"36","villageCode":"22","villageName":"TE AMYEL","created_at":"2022-01-01 10:32:11","updated_at":"2022-01-01 10:32:11"},{"id":"5868","districtCode":"88","countyCode":"160","subCountyCode":"7","parishCode":"36","villageCode":"27","villageName":"WIANYANGA","created_at":"2022-01-01 10:33:37","updated_at":"2022-01-01 10:33:37"}]


onFailure
Failure Data samples
if a wrong code for district, county , subcounty  and parishCode are provided or simply no results found  :[{"message":"No Villages available"}]
if wrong parameters passed:[{"message":"Wrong Parameters Passed"}]


===============================