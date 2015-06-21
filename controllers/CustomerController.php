<?php

namespace app\controllers;
//namespace app\modules\api\controllers;
 
use Yii;
use app\models\Customer;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
 
class CustomerController extends Controller
{

	 public function behaviors()
    {
    return [
        'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
            'index'=>['get'],
            'view'=>['get'],
            'create'=>['post'],
            'update'=>['post'],
            'delete' => ['delete'],
            'deleteall'=>['post'],
        ],
 
        ]
    ];
    }
 
 
    public function beforeAction($event)
    {
    $action = $event->id;
    if (isset($this->actions[$action])) {
        $verbs = $this->actions[$action];
    } elseif (isset($this->actions['*'])) {
        $verbs = $this->actions['*'];
    } else {
        return $event->isValid;
    }
    $verb = Yii::$app->getRequest()->getMethod();
 
      $allowed = array_map('strtoupper', $verbs);
 
      if (!in_array($verb, $allowed)) {
 
        $this->setHeader(400);
        echo json_encode(array('status'=>0,'error_code'=>400,'message'=>'Method not allowed'),JSON_PRETTY_PRINT);
        exit;
 
    }  
 
      return true;  
    }
 

    public function actionIndex()
    {
 
          $params=$_REQUEST;
          $filter=array();
          $sort="";
 
          $page=1;
          $limit=10;
 
           if(isset($params['page']))
             $page=$params['page'];
 
 
           if(isset($params['limit']))
              $limit=$params['limit'];
 
            $offset=$limit*($page-1);
 
 
            /* Filter elements */
           if(isset($params['filter']))
            {
             $filter=(array)json_decode($params['filter']);
            }
 
             if(isset($params['datefilter']))
            {
             $datefilter=(array)json_decode($params['datefilter']);
            }
 
 
            if(isset($params['sort']))
            {
              $sort=$params['sort'];
         if(isset($params['order']))
        {  
            if($params['order']=="false")
             $sort.=" desc";
            else
             $sort.=" asc";
 
        }
            }
 
 
               $query=new Query;
               $query->offset($offset)
                 ->limit($limit)
                 ->from('customer')
             /*    ->andFilterWhere(['like', 'id', $filter['id']])
                 ->andFilterWhere(['like', 'name', $filter['name']])
                 ->andFilterWhere(['like', 'family', $filter['family']])
		 ->andFilterWhere(['like', 'mobile', $filter['mobile']])
		 ->andFilterWhere(['like', 'home', $filter['home']])
		 ->andFilterWhere(['like', 'work', $filter['work']])    */
                 ->orderBy($sort)
                 ->select("id,name,family,mobile,home,work");
 
           $command = $query->createCommand();
               $models = $command->queryAll();
 
               $totalItems=$query->count();
 
          $this->setHeader(200);
 
          echo json_encode(array('status'=>1,'data'=>$models,'totalItems'=>$totalItems),JSON_PRETTY_PRINT);
 
    }
          /* Functions to set header with status code. eg: 200 OK ,400 Bad Request etc..*/      
private function setHeader($status)
  {
 
      $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
      $content_type="application/json; charset=utf-8";
 
      header($status_header);
      header('Content-type: ' . $content_type);
      header('X-Powered-By: ' . "Nintriva <nintriva.com>");
  }
  private function _getStatusCodeMessage($status)
  {
      $codes = Array(
      200 => 'OK',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      );
      return (isset($codes[$status])) ? $codes[$status] : '';
  }

 public function actionCreate()
    {
 
    $params=$_REQUEST;
    $model = new Customer();
    $model->attributes=$params;
 
 
 
    if ($model->save()) {
 
        $this->setHeader(200);
        echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
 
    } 
    else
    {
        $this->setHeader(400);
        echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
    }
 
    }
 
/**
    * Displays a single User model.
    * @param integer $id
    * @return mixed
    */
    public function actionView($id)
    {
 
      $model=$this->findModel($id);
 
      $this->setHeader(200);
      echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
 
    }


 protected function findModel($id)
    {
    if (($model = Customer::findOne($id)) !== null) {
        return $model;
    } else {
 
      $this->setHeader(400);
      echo json_encode(array('status'=>0,'error_code'=>400,'message'=>'Bad request'),JSON_PRETTY_PRINT);
      exit;
    }
    }
 
    

/**
    * Deletes an existing multiple User models at a time.
    * @return json
    */
    public function actionDeleteall()
    {
    $ids=json_decode($_REQUEST['ids']);
 
    $data=array();
 
    foreach($ids as $id)
    {
      $model=$this->findModel($id);
 
      if($model->delete())
        $data[]=array_filter($model->attributes);
      else
      {
        $this->setHeader(400);
        echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
        return;
      }  
    }
 
    $this->setHeader(200);
    echo json_encode(array('status'=>1,'data'=>$data),JSON_PRETTY_PRINT);
 
    }
 
public function actionDelete($id)
  {
      $model=$this->findModel($id);
 
      if($model->delete())
      { 
      $this->setHeader(200);
      echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
 
      }
      else
      {
 
      $this->setHeader(400);
      echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
      }
 
  }


public function actionUpdate($id)
  {
      $params=$_REQUEST;
 
      $model = $this->findModel($id);
 
      $model->attributes=$params;
 
      if ($model->save()) {
 
      $this->setHeader(200);
      echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
 
      } 
      else
      {
      $this->setHeader(400);
      echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
      }
 
  }


}
?>
