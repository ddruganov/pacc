<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\DeleteAction;
use core\actions\generic\SaveAction;
use core\actions\generic\ToggleAction;
use core\collectors\coach\CoachAllCollector;
use core\models\user\Coach;
use yii\web\Controller;

class CoachController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => CoachAllCollector::class
            ],
            'autocomplete' => [
                'class' => CollectorAction::class,
                'collectorClass' => CoachAllCollector::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Coach::class
            ],
            'toggle' => [
                'class' => ToggleAction::class,
                'modelClass' => Coach::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Coach::class
            ]
        ];
    }
}
