<?php

namespace core\collectors\passInstance;

use core\collectors\AbstractDataCollector;
use core\components\exceptions\pass\PassNotFoundException;
use core\models\pass\PassInstance;

class PassInstanceOneCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $passInstanceId = $this->getParam('id');
        $passInstance = PassInstance::findOne($passInstanceId);
        if (!$passInstance) {
            throw new PassNotFoundException($passInstanceId);
        }

        return [
            'id' => $passInstance->id,
            'passId' => $passInstance->passId,
            'clientId' => $passInstance->clientId,
            'hours' => $passInstance->hours,
            'price' => $passInstance->price,
            'name' => $passInstance->name,
            'creationDate' => $passInstance->creationDate,
            'expirationDate' => $passInstance->expirationDate,
            'activityId' => $passInstance->activityId
        ];
    }
}
