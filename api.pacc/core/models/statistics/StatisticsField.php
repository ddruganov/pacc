<?php

namespace core\models\statistics;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;
use yii\db\Query;

/**
 * This is the model class for table "statistics.statistics_field".
 *
 * @property int $id
 * @property int $typeId
 * @property int $weight
 */
class StatisticsField extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'statistics.statistics_field';
    }

    public function rules()
    {
        return [
            [['id', 'weight'], 'required'],
            [['id', 'type_id', 'weight'], 'integer'],
        ];
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if (isset($attributes['id'])) {
            $model = self::findOne($attributes['id']);
        }
        $model ??= new self();
        $model->setAttributes($attributes);

        $parent = StatisticsComponent::findOne($model->id);

        if (!$model->weight) {
            $model->weight = ((new Query())
                ->select(['sf.weight'])
                ->from(['sc' => StatisticsComponent::tableName()])
                ->innerJoin(['sf' => self::tableName()], 'sf.id = sc.id')
                ->where(['sc.statistics_id' => $parent->statisticsId])
                ->orderBy(['sf.weight' => SORT_DESC])
                ->limit(1)
                ->scalar() ?: 0) + 10;
        }

        if (!$model->isNewRecord && $model->isAttributeChanged('weight')) {
            if ($model->weight % 10) {
                $model->weight = (int)(ceil($model->weight / 10) * 10);
            }
            // берём только те ноды, которые пойдут после текущей
            $siblings = self::find()
                ->alias('sf')
                ->where(['sc.statistics_id' => $parent->statisticsId])
                ->andWhere(['>=', 'sf.weight', $model->weight])
                ->orderBy('sf.weight')
                ->innerJoin(['sc' => StatisticsComponent::tableName()], 'sc.id = sf.id')
                ->all();
            foreach ($siblings as $index => $sibling) {
                $sibling->weight = $model->weight + (($index + 1) * 10);
                if (!$sibling->save()) {
                    return new ExecutionResult(false, $sibling->getFirstErrors());
                }
            }
        }


        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion
}
