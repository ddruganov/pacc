<?php

namespace core\models\news;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\components\toggle\ToggleableInterface;

/**
 * This is the model class for table "news.article".
 *
 * @property int $id
 * @property int $organizationId
 * @property string $creationDate
 * @property string $showAfterDate
 * @property string $showBeforeDate
 * @property string $title
 * @property string $contents
 * @property bool $active
 */
class Article extends ExtendedActiveRecord implements SaveableInterface, ToggleableInterface
{
    public static function tableName()
    {
        return 'news.article';
    }

    public function rules()
    {
        return [
            [['organization_id', 'creation_date'], 'required'],
            [['organization_id'], 'integer'],
            [['creation_date', 'show_after_date', 'show_before_date', 'title', 'contents'], 'string'],
            [['active'], 'boolean']
        ];
    }

    #region ToggleableInterface
    public function toggle(): self
    {
        $this->active = !$this->active;
        return $this;
    }
    #endregion

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = isset($attributes['id']) ? self::findOne($attributes['id']) : new self();
        $model->setAttributes($attributes);
        $model->isNewRecord && $model->setAttributes([
            'creationDate' => DateHelper::now(),
            'active' => true
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors(), ['id' => $model->id]);
    }
    #endregion
}
