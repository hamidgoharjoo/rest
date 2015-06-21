<?php
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use kartik\grid\GridView;
?>
<?= Html::encode($message) ?>


<?php
$id=$_POST['id'];
$url = "http://localhost/rest1/web/index.php/customer/view?id=".$id;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
$json = curl_exec($ch);
if(!$json) {
    echo curl_error($ch);
}
curl_close($ch);
header('Content-Type: application/json');
$json=json_decode($json);

$dataProvider = new ArrayDataProvider([
'allModels'  => $json,
//'sort' =>['attributes' => ['ID', 'Description'],],
'pagination' => ['pageSize' => 5]
]);



echo GridView::widget([
    'dataProvider'=> $dataProvider,
    
    //'filterModel' => $searchModel,
    'columns' => [
    ['class' => '\kartik\grid\DataColumn'],
    'id',
    'name',
    'family',
    'mobile',
    'home',
     'work',
    ['class' => '\kartik\grid\ActionColumn'],
],
    'responsive'=>true,
    'hover'=>true,
    'export'=>false,
    
]);


?>
