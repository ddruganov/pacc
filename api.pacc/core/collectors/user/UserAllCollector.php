<?php

namespace core\collectors\user;

use core\collectors\PagedDataCollector;
use core\models\organization\OrganizationUser;
use core\models\user\User;
use yii\db\Query;

class UserAllCollector extends PagedDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select(['ou.id', 'u.name', 'u.email', 'u.creation_date', 'ou.active'])
            ->from(['u' => User::tableName()])
            ->innerJoin(['ou' => OrganizationUser::tableName()], 'ou.user_id = u.id')
            ->where(['ou.organization_id' => $this->getParam('organizationId')]);

        // name
        if ($name = $this->getParam('filter.name')) {
            $query->andWhere(['ilike', 'u.name', $name]);
        }
        // email
        if ($email = $this->getParam('filter.email')) {
            $query->andWhere(['ilike', 'u.email', $email]);
        }

        $query->orderBy('u.id desc');

        $pageCount = $this->getPageCount();

        if ($page = $this->getParam('page')) {
            $this->setPage($page);
        }

        $users = $query->all();

        foreach ($users as $key => $user) {
            $users[$key]['creationDate'] = date('d.m.Y', strtotime($user['creation_date']));
            unset($users[$key]['creation_date']);
        }

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'models' => $users
        ];
    }
}
