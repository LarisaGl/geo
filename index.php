<?php

require __DIR__ . '/vendor/autoload.php';

$api = new \Yandex\Geo\Api();

if (isset($_POST['find'])) {

    $api->setQuery($_POST['find']);

    $api->load();

    $response = $api->getResponse();

    $arr=[];
    $showmap=false;

    $collection = $response->getList();
    foreach ($collection as $item) {
        $arr[]=[
          'latitude'=>$item->getLatitude(),
          'longitude'=>$item->getLongitude(),
          'address'=>$item->getAddress()
        ];
    }
    if ($response->getFoundCount()==1) {
      $showmap=true;
      $point="[{$arr[0]['latitude']},{$arr[0]['longitude']}]";
    }
} elseif (isset($_GET['point'])) {
  $showmap=true;
  $point=$_GET['point'];
  } else {
  $showmap=false;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>GEO</title>
  <meta charset="UTF-8">
  <?php
  if($showmap) { ?>
  <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

  <script type="text/javascript">
       ymaps.ready(init);
    
      function init(){ 
          var myMap = new ymaps.Map("map", {
              center: <?=$point ?>,
              zoom: 13
          }); 
          var myPlacemark = new ymaps.Placemark(<?=$point ?>, {
              hintContent: 'Содержимое всплывающей подсказки',
              balloonContent: 'Содержимое балуна'
          });
            
          myMap.geoObjects.add(myPlacemark);
      }
  </script>
  <?php } ?>
</head>

<body>
<form action="index.php" method="POST" enctype="multipart/form-data">
  <p>Введите адрес:</p>
  <input type="text" name="find"><br>
  <input type="submit" value="Найти адрес">  
</form>

<?php
if (isset($arr)) {
foreach ($arr as $value) { ?>
  <a href="?point=[<?=$value['latitude']?>,<?=$value['longitude']?>]"><?=$value['address']?></a><br>
  <?=$value['latitude']?><br>
  <?=$value['longitude']?><br>
<?php }
}?>

<div id="map" style="width: 600px; height: 400px"></div>

</body>
</html>