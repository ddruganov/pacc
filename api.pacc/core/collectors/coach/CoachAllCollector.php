<?php

namespace core\collectors\coach;

use core\collectors\PagedDataCollector;
use core\models\common\Activity;
use core\models\organization\OrganizationUser;
use core\models\user\Coach;
use core\models\user\User;
use yii\db\Query;

class CoachAllCollector extends PagedDataCollector
{
    public function get(): array
    {
        $this->query = (new Query())
            ->select(['coach.id', 'payRate' => 'coach.pay_rate', 'coach.active', 'ou.name', 'ou.creation_date', 'u.email', 'activityId' => 'activity.id'])
            ->from(['coach' => Coach::tableName()])
            ->innerJoin(['ou' => OrganizationUser::tableName()], 'ou.id = coach.organization_user_id')
            ->innerJoin(['u' => User::tableName()], 'u.id = ou.user_id')
            ->innerJoin(['activity' => Activity::tableName()], 'activity.id = coach.activity_id')
            ->where(['ou.organization_id' => $this->getParam('organizationId')])
            ->andWhere(['coach.deleted' => false])
            ->orderBy('coach.id desc');

        // name
        if ($name = $this->getParam('filter.name')) {
            $this->query->andWhere(['ilike', 'ou.name', $name]);
        }
        // email
        if ($email = $this->getParam('filter.email')) {
            $this->query->andWhere(['ilike', 'u.email', $email]);
        }
        // payrate
        if ($payRate = $this->getParam('filter.payRate')) {
            $this->query->andWhere(['>=', 'coach.pay_rate', $payRate]);
        }

        $this->query->orderBy('ou.id desc');

        $pageCount = $this->getPageCount();

        if ($page = $this->getParam('page')) {
            $this->setPage($page);
        }

        $coaches = $this->query->all();

        foreach ($coaches as $key => $client) {
            $coaches[$key]['creationDate'] = date('d.m.Y', strtotime($client['creation_date']));
            unset($coaches[$key]['creation_date']);
        }

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'models' => $coaches
        ];
    }
}
