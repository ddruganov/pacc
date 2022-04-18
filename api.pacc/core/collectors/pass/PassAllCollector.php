<?php

namespace core\collectors\pass;

use core\collectors\PagedDataCollector;
use core\models\common\Activity;
use core\models\pass\Pass;
use yii\db\Query;

class PassAllCollector extends PagedDataCollector
{
    public function get(): array
    {
        $this->query = (new Query())
            ->select([
                'pass.id',
                'pass.hours',
                'pass.price',
                'pass.active',
                'expiresIn' => 'pass.expires_in',
                'name' => 'coalesce(pass.name, \'Безымянный абонемент\')',
                'activityName' => 'activity.name'
            ])
            ->from(['pass' => Pass::tableName()])
            ->leftJoin(['activity' => Activity::tableName()], 'activity.id = pass.activity_id')
            ->where(['pass.deleted' => false])
            ->andWhere(['pass.organization_id' => $this->getParam('organizationId')])
            ->orderBy(['pass.id' => SORT_DESC]);

        // name
        if ($name = $this->getParam('filter.name')) {
            $this->query->andWhere(['ilike', 'pass.name', $name]);
        }
        // hours
        if ($hoursFrom = $this->getParam('filter.hours.from')) {
            $this->query->andWhere(['>=', 'pass.hours', $hoursFrom]);
        }
        if ($hoursTo = $this->getParam('filter.hours.to')) {
            $this->query->andWhere(['<=', 'pass.hours', $hoursTo]);
        }
        // expiresIn
        if ($expiresInFrom = $this->getParam('filter.expiresIn.from')) {
            $this->query->andWhere(['>=', 'pass.expires_in', $expiresInFrom]);
        }
        if ($expiresInTo = $this->getParam('filter.expiresIn.to')) {
            $this->query->andWhere(['<=', 'pass.expires_in', $expiresInTo]);
        }
        // price
        if ($priceFrom = $this->getParam('filter.price.from')) {
            $this->query->andWhere(['>=', 'pass.price', $priceFrom]);
        }
        if ($priceTo = $this->getParam('filter.price.to')) {
            $this->query->andWhere(['<=', 'pass.price', $priceTo]);
        }
        // activityId
        if ($activityId = $this->getParam('filter.activityId')) {
            $this->query->andWhere(['pass.activity_id' => $activityId]);
        }
        // active
        if ($active = $this->getParam('filter.active')) {
            $this->query->andWhere(['pass.active' => $active]);
        }

        $pageCount = $this->getPageCount();

        if ($page = $this->getParam('page')) {
            $this->setPage($page);
        }

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'models' => $this->query->all()
        ];
    }
}
