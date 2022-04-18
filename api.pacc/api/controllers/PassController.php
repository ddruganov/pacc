<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\DeleteAction;
use core\actions\generic\SaveAction;
use core\actions\generic\ToggleAction;
use core\collectors\pass\PassAllCollector;
use core\collectors\pass\PassOneCollector;
use core\models\pass\Pass;
use yii\web\Controller;

class PassController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => PassAllCollector::class
            ],
            'autocomplete' => [
                'class' => CollectorAction::class,
                'collectorClass' => PassAllCollector::class
            ],
            'getOne' => [
                'class' => CollectorAction::class,
                'collectorClass' => PassOneCollector::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Pass::class
            ],
            'toggle' => [
                'class' => ToggleAction::class,
                'modelClass' => Pass::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Pass::class
            ]
        ];
    }
}
