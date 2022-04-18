<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\SaveAction;
use core\collectors\passInstance\PassInstanceAllCollector;
use core\collectors\passInstance\PassInstanceOneCollector;
use core\models\pass\PassInstance;
use core\models\pass\Visit;
use yii\web\Controller;

class PassInstanceController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => PassInstanceAllCollector::class
            ],
            'getOne' => [
                'class' => CollectorAction::class,
                'collectorClass' => PassInstanceOneCollector::class
            ],
            'create' => [
                'class' => SaveAction::class,
                'modelClass' => PassInstance::class
            ],
            'visit' => [
                'class' => SaveAction::class,
                'modelClass' => Visit::class
            ]
        ];
    }
}
