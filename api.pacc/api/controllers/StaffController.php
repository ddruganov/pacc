<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\SaveAction;
use core\collectors\user\UserAllCollector;
use core\collectors\user\UserOneCollector;
use core\models\user\User;
use yii\web\Controller;

class StaffController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => UserAllCollector::class
            ],
            'autocomplete' => [
                'class' => CollectorAction::class,
                'collectorClass' => UserAllCollector::class
            ],
            'getOne' => [
                'class' => CollectorAction::class,
                'collectorClass' => UserOneCollector::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => User::class
            ]
        ];
    }
}
