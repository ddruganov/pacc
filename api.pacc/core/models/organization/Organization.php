<?php

namespace core\models\organization;

use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\components\typedField\ImageTypedField;
use core\models\common\ModelType;
use core\models\service\Image;
use core\models\service\ModelImage;
use core\models\user\User;

/**
 * This is the model class for table "organization.organization".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $hash
 * @property string $creationDate
 * @property int $creatorId
 */
class Organization extends ExtendedActiveRecord implements SaveableInterface
{
    public ImageTypedField $logo;

    public static function tableName()
    {
        return 'organization.organization';
    }

    public function rules()
    {
        return [
            [['name', 'hash', 'creation_date', 'creator_id'], 'required'],
            [['name', 'hash', 'creation_date', 'description'], 'string'],
            [['creator_id'], 'integer']
        ];
    }

    public function getSettings(): ?OrganizationSettings
    {
        return OrganizationSettings::findOne($this->id);
    }

    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if (isset($attributes['organizationId'])) {
            $model = self::findOne($attributes['organizationId']);
        }
        $model ??= new self();
        $model->setAttributes([
            'name' => isset($attributes['name']) ? $attributes['name'] : $model->name,
            'description' => isset($attributes['description']) ? $attributes['description'] : $model->description,
            'logo' => isset($attributes['logo']) ? $attributes['logo'] : $model->logo->getData(),
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors(), ['id' => $model->id]);
    }

    public function getLogo(): ?array
    {
        $modelImage = ModelImage::findOne(['model_id' => $this->id, 'model_type_id' => ModelType::ORGANIZATION, 'model_property_name' => 'logo']);
        $imageId = $modelImage ? $modelImage->imageId : null;
        return Image::getData($imageId);
    }

    public static function new(User $creator): ExecutionResult
    {
        $now = DateHelper::now();
        $model = new self([
            'name' => 'Организация пользователя ' . $creator->name,
            'hash' => md5(join('ORG', [$creator->name, $now, md5(rand())])),
            'creationDate' => $now,
            'creatorId' => $creator->id
        ]);
        return  new ExecutionResult($model->save(), $model->getFirstErrors(), ['id' => $model->id]);
    }
}
