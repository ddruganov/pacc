<?php

namespace core\models\organization;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "organization.organization_settings".
 *
 * @property int $id
 */
class OrganizationSettings extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'organization.organization_settings';
    }

    public function rules()
    {
        return [];
    }
}
