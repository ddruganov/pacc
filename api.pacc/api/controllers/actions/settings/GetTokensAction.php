<?php

namespace api\controllers\actions\settings;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\DateHelper;
use core\components\helpers\UserHelper;
use core\models\common\ModelType;
use core\models\token\RefreshToken;
use yii\db\Query;

class GetTokensAction extends ApiAction
{
    public function run()
    {
        $models = (new Query())
            ->select([
                'id',
                'issueDate' => 'issue_date',
                'userAgent' => 'user_agent'
            ])
            ->from(RefreshToken::tableName())
            ->where([
                'model_id' => UserHelper::id(),
                'model_type_id' => ModelType::USER
            ])
            ->andWhere(['>', 'expiration_date', DateHelper::now()])
            ->all();

        foreach ($models as $key => $value) {
            $models[$key]['issueDate'] = date('d.m.Y H:i', strtotime($value['issueDate']));
        }

        return $this->apiResponse(new ExecutionResult(true, [], $models));
    }
}
