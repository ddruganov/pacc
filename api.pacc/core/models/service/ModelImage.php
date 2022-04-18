<?php

namespace core\models\service;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "service.model_image".
 *
 * @property int $id
 * @property int $modelTypeId
 * @property int $modelId
 * @property string $modelPropertyName
 * @property int $imageId
 */
class ModelImage extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'service.model_image';
    }

    public function rules()
    {
        return [
            [['model_type_id', 'model_id', 'model_property_name', 'image_id'], 'required'],
            [['model_type_id', 'model_id', 'image_id'], 'integer'],
            [['model_property_name'], 'string'],
        ];
    }

    public function delete()
    {
        if (parent::delete() === false) {
            return false;
        }

        $image = Image::findOne($this->imageId);
        if ($image && $image->delete() === false) {
            $this->addErrors($image->getErrors());
            return false;
        }

        return true;
    }
}
