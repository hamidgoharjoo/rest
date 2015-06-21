<?php

namespace app\controllers;

use yii\web\Controller;

class MainController extends Controller
{
    // ...existing code...

    public function actionIndex($message = 'Hello')
    {
        return $this->render('index', ['message' => $message]);
    }
}
?>
