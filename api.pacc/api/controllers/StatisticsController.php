<?php

namespace api\controllers;

use api\controllers\actions\statistics\StatisticsDownloadAction;
use core\actions\generic\CollectorAction;
use core\actions\generic\DeleteAction;
use core\actions\generic\SaveAction;
use core\collectors\statistics\StatisticsAllCollector;
use core\collectors\statistics\StatisticsCommonCollector;
use core\collectors\statistics\StatisticsOneCollector;
use core\collectors\statistics\StatisticsViewCollector;
use core\models\statistics\Statistics;
use core\models\statistics\StatisticsComponent;
use yii\web\Controller;

class StatisticsController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => StatisticsAllCollector::class
            ],
            'view' => [
                'class' => CollectorAction::class,
                'collectorClass' => StatisticsViewCollector::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Statistics::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Statistics::class
            ],
            'getOne' => [
                'class' => CollectorAction::class,
                'collectorClass' => StatisticsOneCollector::class
            ],
            'common' => [
                'class' => CollectorAction::class,
                'collectorClass' => StatisticsCommonCollector::class
            ],
            'download' => StatisticsDownloadAction::class,
            'saveComponent' => [
                'class' => SaveAction::class,
                'modelClass' => StatisticsComponent::class
            ],
            'deleteComponent' => [
                'class' => DeleteAction::class,
                'modelClass' => StatisticsComponent::class
            ],
        ];
    }
}
