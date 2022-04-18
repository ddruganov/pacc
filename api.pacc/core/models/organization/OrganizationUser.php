<?php

namespace core\models\organization;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "organization.organization_user".
 *
 * @property int $id
 * @property int $organizationId
 * @property int $userId
 * @property bool $isDefault
 * @property int $userRoleId
 * @property bool $active
 * @property string $name
 * @property string $creationDate
 */
class OrganizationUser extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'organization.organization_user';
    }

    public function rules()
    {
        return [
            [['organization_id', 'user_id', 'is_default', 'user_role_id', 'active', 'name', 'creation_date'], 'required'],
            [['organization_id', 'user_id', 'user_role_id'], 'integer'],
            [['is_default', 'active'], 'boolean'],
            [['name', 'creation_date'], 'string']
        ];
    }
}
