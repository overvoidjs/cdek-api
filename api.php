<?php

use CdekSDK\Requests;
require_once './vendor/autoload.php';


$city = $_GET['city'];

$mycitys = file_get_contents("ru_city.json");

$citys_arr = json_decode($mycitys,true);

$city_id = '';
$city_name = '';

for ($i=0; $i < count($citys_arr); $i++) {
  if($citys_arr[$i]["C"] == $city){
   $city_name = $citys_arr[$i]["C"];
    $city_id = $citys_arr[$i]["Q"];
    $i = count($citys_arr) +1 ;
  }
}
$city_id = (int)$city_id;

$client = new \CdekSDK\CdekClient('cdek_api_user', 'cdek_api_passwd');
$request = new Requests\PvzListRequest();
$request->setCityId($city_id);
$request->setType(Requests\PvzListRequest::TYPE_ALL);
$request->setCashless(true);
$request->setCodAllowed(true);
$request->setDressingRoom(true);
$response = $client->sendPvzListRequest($request);


if(isset($_GET['debug'])){
echo "<cityCode>".$city_id."</cityCode>\n";
if ($response->hasErrors()) {
    // обработка ошибок
}
$d = array();
foreach ($response as $item) {
    /** @var \CdekSDK\Common\Pvz $item */
    // всевозможные параметры соответствуют полям из API СДЭК
    $try_name = str_replace('"', '', $item->Name);


    $d[] = array('code' => "$item->Code" ,'name' => "$try_name", 'addr'=>"$item->Address");

     $item->Code;
     $item->Name;
     $item->Address;
    foreach ($item->OfficeImages as $image) {
        $image->getUrl();
    }


}
var_dump($d);

}

else {
header('Content-Type: application/json');
$d = array();
foreach ($response as $item) {
    /** @var \CdekSDK\Common\Pvz $item */
    // всевозможные параметры соответствуют полям из API СДЭК
    $try_name = str_replace('"', '', $item->Name);
    $photo = '';
    foreach ($item->OfficeImages as $image) {
      $photo =   $image->getUrl();
    }

    $d[] = array(
      'cityName' => "$item->RegionName",
      'cityCode' => "$city_id",
      'code' => "$item->Code",
      'name' => "$try_name",
      'addr'=>"$item->Address",
      'addrComm'=>"$item->AddressComment",
      'fullAddr'=>"$item->FullAddress",
      'postCode'=>"$item->PostalCode",
      'phone'=>"$item->Phone",
      'email'=>"$item->Email",
      'country'=>"$item->CountryName",
      'IsDressingRoom'=>"$item->IsDressingRoom",
      'HaveCashless'=>"$item->HaveCashless",
      'NearestStation'=>"$item->NearestStation",
      'MetroStation'=>"$item->MetroStation",
      'workTime'=>"$item->WorkTime",
      'photo'=>"$photo",
    );


    // echo '['.json_encode($item).']';
     $item->Code;
     $item->Name;
     $item->Address;






}
    echo json_encode($d);
}
?>
