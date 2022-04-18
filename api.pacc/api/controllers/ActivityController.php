<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\DeleteAction;
use core\actions\generic\SaveAction;
use core\collectors\activity\ActivityAllCollector;
use core\models\common\Activity;
use yii\web\Controller;

class ActivityController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => ActivityAllCollector::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Activity::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Activity::class
            ],
        ];
    }
}
