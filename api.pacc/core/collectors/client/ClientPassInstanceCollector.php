<?php

namespace core\collectors\client;

use core\collectors\PagedDataCollector;
use core\components\helpers\DateHelper;
use core\models\common\Activity;
use core\models\organization\OrganizationClient;
use core\models\organization\OrganizationUser;
use core\models\pass\PassInstance;
use core\models\pass\Visit;
use core\models\user\Coach;
use yii\db\Query;

class ClientPassInstanceCollector extends PagedDataCollector
{
    public function get(): array
    {
        $hoursSpentSubquery = (new Query())
            ->select('count(hours_spent)')
            ->from(Visit::tableName())
            ->where('pass_instance_id = pi.id');

        $lastVisitSubquery = (new Query())
            ->select('datetime')
            ->from(Visit::tableName())
            ->where('pass_instance_id = pi.id')
            ->orderBy(['datetime' => SORT_DESC])
            ->limit(1);

        $query = (new Query())
            ->select([
                'pi.id',
                'pi.name',
                'pi.hours',
                'pi.price',
                'pi.creation_date',
                'pi.expiration_date',
                'activityName' => 'activity.name',
                'coachName' => 'ou.name',
                'hoursSpent' => $hoursSpentSubquery,
                'lastVisitDate' => $lastVisitSubquery
            ])
            ->from(['oc' => OrganizationClient::tableName()])
            ->innerJoin(['pi' => PassInstance::tableName()], 'pi.organization_client_id = oc.id')
            ->innerJoin(['activity' => Activity::tableName()], 'activity.id = pi.activity_id')
            ->leftJoin(['coach' => Coach::tableName()], 'coach.id = pi.coach_id')
            ->leftJoin(['ou' => OrganizationUser::tableName()], 'ou.id = coach.organization_user_id')
            ->where(['oc.id' => $this->getParam('organizationClientId')])
            ->orderBy(['pi.id' => SORT_DESC]);

        if ($expirationDateFrom = $this->getParam('expirationDate.from')) {
            $query->andWhere(['>=', 'pi.expiration_date', $expirationDateFrom]);
        }

        $pageCount = $this->getPageCount();

        if ($page = $this->getParam('page')) {
            $this->setPage($page);
        }

        $passInstances = $query->all();

        foreach ($passInstances as $key => $passInstance) {
            $passInstances[$key]['creationDate'] = date('d.m.Y', strtotime($passInstance['creation_date']));
            $passInstances[$key]['expirationDate'] = date('d.m.Y', strtotime($passInstance['expiration_date']));
            $passInstances[$key]['isExpired'] = DateHelper::now()  > $passInstance['expiration_date'];
            $passInstances[$key]['lastVisitDate'] = $passInstance['lastVisitDate']  ? date('d.m.Y H:i', strtotime($passInstance['lastVisitDate'])) : null;

            unset($passInstances[$key]['creation_date'], $passInstances[$key]['expiration_date']);
        }

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'models' => $passInstances
        ];
    }
}
