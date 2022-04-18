<?php

namespace core\components\typedField;

use core\components\ExecutionResult;
use core\models\common\ModelType;
use core\models\service\ModelImage;

class ImageTypedField extends TypedField
{
    #region TypeFieldInterface
    public function init(): void
    {
        $modelType = ModelType::findOne(['table_name' => $this->parent::tableName()]);
        $modelImage = ModelImage::findOne([
            'model_id' => $this->parent->id,
            'model_type_id' => $modelType->id,
            'model_property_name' => $this->fieldName
        ]);

        $this->setData(['id' => $modelImage ? $modelImage->imageId : null]);
    }

    public function saveData(): ExecutionResult
    {
        $imageId = $this->getData('id');
        if (!$imageId) {
            return $this->deleteData();
        }

        $modelType = ModelType::findOne(['table_name' => $this->parent::tableName()]);

        $modelImageAttributes = [
            'model_id' => $this->parent->id,
            'model_type_id' => $modelType->id,
            'model_property_name' => $this->fieldName,
            'image_id' => $imageId
        ];
        $model = ModelImage::findOne($modelImageAttributes) ?: new ModelImage($modelImageAttributes);

        return new ExecutionResult($model->isNewRecord ? $model->save() : true, $model->getFirstErrors());
    }

    public function deleteData(): ExecutionResult
    {
        $modelType = ModelType::findOne(['table_name' => $this->parent::tableName()]);
        $modelImageAttributes = [
            'model_id' => $this->parent->id,
            'model_type_id' => $modelType->id,
            'model_property_name' => $this->fieldName
        ];

        $imageId = $this->getData('id');
        if (!$imageId) {
            $modelImage = ModelImage::findOne($modelImageAttributes);
            $imageId = $modelImage ? $modelImage->imageId : null;
        }

        if (!$imageId) {
            return new ExecutionResult(true);
        }

        $modelImageAttributes['image_id'] = $imageId;
        $modelImage = ModelImage::findOne($modelImageAttributes);

        if (!$modelImage) {
            return new ExecutionResult(true);
        }

        return new ExecutionResult($modelImage->delete() !== false, $modelImage->getFirstErrors());
    }
    #endregion
}
