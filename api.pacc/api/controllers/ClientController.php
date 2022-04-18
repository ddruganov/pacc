<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\DeleteAction;
use core\actions\generic\SaveAction;
use core\collectors\client\ClientPassInstanceCollector;
use core\collectors\client\ClientAllCollector;
use core\collectors\client\ClientOneCollector;
use core\collectors\client\ClientVisitCollector;
use core\models\client\Client;
use core\models\organization\OrganizationClient;
use yii\web\Controller;

class ClientController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => ClientAllCollector::class
            ],
            'autocomplete' => [
                'class' => CollectorAction::class,
                'collectorClass' => ClientAllCollector::class
            ],
            'getOne' => [
                'class' => CollectorAction::class,
                'collectorClass' => ClientOneCollector::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Client::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => OrganizationClient::class,
                'idField' => 'organizationClientId'
            ],
            'getPassInstances' => [
                'class' => CollectorAction::class,
                'collectorClass' => ClientPassInstanceCollector::class
            ],
            'getVisits' => [
                'class' => CollectorAction::class,
                'collectorClass' => ClientVisitCollector::class
            ]
        ];
    }
}
