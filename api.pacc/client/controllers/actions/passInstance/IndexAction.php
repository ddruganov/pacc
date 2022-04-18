<?php

namespace client\controllers\actions\passInstance;

use core\actions\ClientSecureAction;
use core\collectors\client\ClientPassInstanceCollector;
use core\components\helpers\ClientHelper;
use Yii;

class IndexAction extends ClientSecureAction
{
    public function run()
    {
        $client = ClientHelper::get();

        $collector = new ClientPassInstanceCollector();
        $collector->setParams([
            'clientId' => $client->id,
            'organizationId' => ClientHelper::getDefaultOrganizationId()
        ]);
        if (Yii::$app->request->post('unexpiredOnly')) {
            $collector->addParams([
                'filter' => [
                    'expirationDate' => [
                        'from' => date('Y-m-d')
                    ]
                ]
            ]);
        }

        return $this->controller->render('index', [
            'passInstances' => $collector->get()['models'],
            'unexpiredOnly' => !Yii::$app->request->post('unexpiredOnly')
        ]);
    }
}
