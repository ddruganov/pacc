<?php

namespace core\collectors\pass;

use core\collectors\AbstractDataCollector;
use core\components\exceptions\pass\PassNotFoundException;
use core\models\pass\Pass;
use yii\web\ForbiddenHttpException;

class PassOneCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $passId = $this->getParam('id');
        $pass = Pass::findOne($passId);
        if (!$pass) {
            throw new PassNotFoundException($passId);
        }
        $organizationId = $this->getParam('organizationId');
        if ($pass->organizationId !== $organizationId) {
            throw new ForbiddenHttpException('Данный абонемент не принадлежит организации #' . $organizationId);
        }

        return [
            'id' => $pass->id,
            'hours' => $pass->hours,
            'price' => $pass->price,
            'active' => $pass->active,
            'expiresIn' => $pass->expiresIn,
            'name' => $pass->name,
            'activityId' => $pass->activityId
        ];
    }

    public function getDataSource(): string
    {
        return parent::DATA_SOURCE_GET;
    }
}
