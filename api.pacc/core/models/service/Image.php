<?php

namespace core\models\service;

use core\components\ExtendedActiveRecord;
use Yii;

/**
 * This is the model class for table "service.image".
 *
 * @property int $id
 * @property int $organizationId
 * @property string $creationDate
 * @property string $extension
 * @property string $hash
 * @property int $size
 */
class Image extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'service.image';
    }

    public function rules()
    {
        return [
            [['organization_id', 'creation_date', 'extension', 'hash', 'size'], 'required'],
            [['organization_id', 'size'], 'integer'],
            [['creation_date', 'extension', 'hash'], 'string'],
        ];
    }

    public function getDirectory(): string
    {
        return Yii::getAlias('@service/web/images/') . $this->creationDate;
    }

    public function getFilepath(): string
    {
        return $this->getDirectory() . '/' . $this->id . '.' . $this->extension;
    }

    public function getUrl(): string
    {
        return Yii::$app->params['hosts']['service'] . '/images/' . $this->creationDate . '/' . $this->id . '.' . $this->extension;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->getUrl()
        ];
    }

    public static function getData(?int $id): array
    {
        $model = self::findOne($id);
        return $model ? $model->asArray() : self::getDefault();
    }

    public static function getDefault(): array
    {
        return [
            'id' => null,
            'url' => Yii::$app->params['hosts']['service'] . '/images/default/no_image.svg'
        ];
    }
}
