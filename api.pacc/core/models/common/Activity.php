<?php

namespace core\models\common;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * This is the model class for table "public.activity".
 *
 * @property int $id
 * @property string $name
 * @property int $organizationId
 * @property bool $deleted
 */
class Activity extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'public.activity';
    }

    public function rules()
    {
        return [
            [['name', 'organization_id', 'deleted'], 'required'],
            [['name'], 'string'],
            [['organization_id'], 'integer'],
            [['deleted'], 'boolean'],
        ];
    }

    public function delete()
    {
        $this->deleted = true;
        return $this->save();
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = isset($attributes['id']) ? self::findOne($attributes['id']) : new self();
        $model->setAttributes($attributes);
        $model->isNewRecord && $model->setAttributes([
            'deleted' => false
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion
}
