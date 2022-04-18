<?php

namespace core\collectors\statistics;

use core\collectors\AbstractDataCollector;
use core\models\common\ModelType;
use core\models\common\TableField;
use core\models\statistics\StatisticsComponent;
use core\models\statistics\StatisticsCondition;
use core\models\statistics\StatisticsConditionType;
use core\models\statistics\StatisticsConditionValue;
use core\models\statistics\StatisticsField;
use core\models\statistics\StatisticsFieldType;
use core\models\statistics\StatisticsOrder;
use yii\db\Query;

class StatisticsComponentAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return [
            'fields' => $this->getFields(),
            'conditions' => $this->getConditions(),
            'order' => $this->getOrder()
        ];
    }

    private function getBaseQuery(): Query
    {
        return (new Query())
            ->select([
                'ft.alias',
                'ft.function',
                'inputType' => 'ft.input_type',
                'fieldName' => 'tf.field',
                'tableName' => 'mt.table_name',
                'tableAlias' => 'mt.table_alias'
            ])
            ->from(['sc' => StatisticsComponent::tableName()])
            ->where(['statistics_id' => $this->getParam('statisticsId')]);
    }

    private function getFields(): array
    {
        $query = $this->getBaseQuery()
            ->addSelect([
                'sf.id',
                'typeId' => 'sf.type_id',
                'sf.weight'
            ])
            ->innerJoin(['sf' => StatisticsField::tableName()], 'sf.id = sc.id')
            ->leftJoin(['ft' => StatisticsFieldType::tableName()], 'ft.id = sf.type_id')
            ->leftJoin(['tf' => TableField::tableName()], 'tf.id = ft.table_field_id')
            ->leftJoin(['mt' => ModelType::tableName()], 'mt.id = tf.model_type_id')
            ->orderBy(['weight' => SORT_ASC]);

        if ($this->getParam('discardBlankFields')) {
            $query->andWhere(['is not', 'sf.type_id', null]);
        }

        return $query->all();
    }

    private function getConditions(): array
    {
        $query = $this->getBaseQuery()
            ->addSelect([
                'cond.id',
                'typeId' => 'cond.type_id',
                'fieldTypeId' => 'cond.field_type_id',
                'ct.operator',
                'ct.name',
                'inputCount' => 'cast(ct.input_count as int)'
            ])
            ->innerJoin(['cond' => StatisticsCondition::tableName()], 'cond.id = sc.id')
            ->leftJoin(['ct' => StatisticsConditionType::tableName()], 'ct.id = cond.type_id')
            ->leftJoin(['ft' => StatisticsFieldType::tableName()], 'ft.id = cond.field_type_id')
            ->leftJoin(['tf' => TableField::tableName()], 'tf.id = ft.table_field_id')
            ->leftJoin(['mt' => ModelType::tableName()], 'mt.id = tf.model_type_id')
            ->orderBy(['id' => SORT_ASC]);

        if ($this->getParam('discardBlankConditions')) {
            $query->andWhere(['is not', 'cond.type_id', null]);
            $query->andWhere(['is not', 'cond.field_type_id', null]);
            $valueCountSubquery = (new Query())
                ->select(['count(id)'])
                ->from(['cv' => StatisticsConditionValue::tableName()])
                ->where('cv.condition_id = cond.id');
            $query->andWhere(['>', $valueCountSubquery, 0]);
        }

        $conditions = $query->all();

        $conditions = array_map(fn (array $condition) => array_merge($condition, ['values' => []]), $conditions);
        $conditions = array_column($conditions, null, 'id');

        $condition_values = (new Query())
            ->select(['condition_id', 'value'])
            ->from([StatisticsConditionValue::tableName()])
            ->where(['in', 'condition_id', array_keys($conditions)])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($condition_values as $condition_value) {
            $conditions[$condition_value['condition_id']]['values'][] = $condition_value['value'];
        }
        return array_values($conditions);
    }

    private function getOrder(): array
    {
        $query = $this->getBaseQuery()
            ->addSelect([
                'so.id',
                'so.direction',
                'fieldTypeId' => 'so.field_type_id'
            ])
            ->innerJoin(['so' => StatisticsOrder::tableName()], 'so.id = sc.id')
            ->leftJoin(['ft' => StatisticsFieldType::tableName()], 'ft.id = so.field_type_id')
            ->leftJoin(['tf' => TableField::tableName()], 'tf.id = ft.table_field_id')
            ->leftJoin(['mt' => ModelType::tableName()], 'mt.id = tf.model_type_id');

        if ($this->getParam('discardBlankOrder')) {
            $query->andWhere(['is not', 'so.field_type_id', null]);
        }

        return $query->one() ?: [];
    }
}
