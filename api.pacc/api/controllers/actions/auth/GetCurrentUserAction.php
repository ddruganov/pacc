<?php

namespace api\controllers\actions\auth;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use core\models\common\ModelType;
use core\models\organization\Organization;
use core\models\organization\OrganizationUser;
use core\models\service\Image;
use core\models\service\ModelImage;
use Yii;
use yii\db\Query;

class GetCurrentUserAction extends ApiAction
{
    public function run()
    {
        $user = UserHelper::get();
        $organizations = (new Query())
            ->select(['o.id', 'o.name', 'o.description', 'isDefault' => 'ou.is_default', 'logoId' => 'mi.image_id', 'o.hash'])
            ->from(['ou' => OrganizationUser::tableName()])
            ->leftJoin(['o' => Organization::tableName()], 'o.id = ou.organization_id')
            ->leftJoin(
                ['mi' => ModelImage::tableName()],
                'mi.model_id = o.id and mi.model_type_id = :org_model_type_id and mi.model_property_name = :org_model_property_name',
                [
                    ':org_model_type_id' => ModelType::ORGANIZATION,
                    ':org_model_property_name' => 'logo'
                ]
            )
            ->where(['ou.user_id' => $user->id])
            ->orderBy('o.id')
            ->all();

        foreach ($organizations as $key => $value) {
            $organizations[$key]['logo'] = Image::getData($value['logoId']);
            $organizations[$key]['inviteLink'] = Yii::$app->params['hosts']['admin'] . '/auth/register?hash=' . $value['hash'];
            unset($organizations[$key]['logoId'], $organizations[$key]['hash']);
        }

        return $this->apiResponse(new ExecutionResult(true, [], [
            'user' => $user->getAttributes(['id', 'name']),
            'organizations' => $organizations
        ]));
    }
}
