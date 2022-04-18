<?php

namespace core\collectors\client;

use core\collectors\PagedDataCollector;
use core\models\client\Client;
use core\models\organization\OrganizationClient;
use yii\db\Query;

class ClientAllCollector extends PagedDataCollector
{
    public function get(): array
    {
        $this->query = (new Query())
            ->select('oc.id, oc.note, oc.name, client.email, oc.creation_date')
            ->from(['client' => Client::tableName()])
            ->innerJoin(['oc' => OrganizationClient::tableName()], 'oc.client_id = client.id')
            ->where(['oc.organization_id' => $this->getParam('organizationId')]);

        // name
        if ($name = $this->getParam('filter.name')) {
            $this->query->andWhere(['ilike', 'oc.name', $name]);
        }
        // email
        if ($email = $this->getParam('filter.email')) {
            $this->query->andWhere(['ilike', 'client.email', $email]);
        }

        $this->query->orderBy('client.id desc');

        $pageCount = $this->getPageCount();

        if ($page = $this->getParam('page')) {
            $this->setPage($page);
        }

        $clients = $this->query->all();

        foreach ($clients as $key => $client) {
            $clients[$key]['creationDate'] = date('d.m.Y', strtotime($client['creation_date']));
            unset($clients[$key]['creation_date']);
        }

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'models' => $clients
        ];
    }
}
