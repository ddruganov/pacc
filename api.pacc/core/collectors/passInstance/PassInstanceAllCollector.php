<?php

namespace core\collectors\passInstance;

use core\collectors\AbstractDataCollector;
use core\models\client\Client;
use core\models\common\Activity;
use core\models\organization\OrganizationClient;
use core\models\organization\OrganizationUser;
use core\models\pass\PassInstance;
use core\models\user\Coach;
use yii\db\Query;

class PassInstanceAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select([
                'pi.id',
                'pi.hours',
                'pi.price',
                'pi.name',
                'pi.creation_date',
                'pi.expiration_date',
                'activityName' => 'activity.name',
                'info' => 'pi.name || \'. \' || cl.name',
                'coachName' => 'ou.name'
            ])
            ->from(['pi' => PassInstance::tableName()])
            ->leftJoin(['activity' => Activity::tableName()], 'activity.id = pi.activity_id')
            ->leftJoin(['oc' => OrganizationClient::tableName()], 'oc.id = pi.organization_client_id')
            ->leftJoin(['cl' => Client::tableName()], 'cl.id = oc.client_id')
            ->leftJoin(['coach' => Coach::tableName()], 'coach.id = pi.coach_id')
            ->leftJoin(['ou' => OrganizationUser::tableName()], 'ou.id = coach.organization_user_id')
            ->where(['oc.id' => $this->getParam('organizationClientId')]);

        $data = $query->all();

        foreach ($data as $key => $value) {
            $data[$key]['creationDate'] = date('d.m.Y H:i', strtotime($value['creation_date']));
            $data[$key]['expirationDate'] = date('d.m.Y H:i', strtotime($value['expiration_date']));
        }

        return $data;
    }
}
