<?php

namespace core\models\common;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "public.model_type".
 *
 * @property int $id
 * @property string $tableName
 * @property string $class
 * @property string $name
 * @property string $tableAlias
 */
class ModelType extends ExtendedActiveRecord
{
    public const USER = 1;
    public const CLIENT = 2;
    public const PASS = 3;
    public const PASS_INSTANCE = 4;
    public const STATISTICS_FIELD = 5;
    public const STATISTICS_CONDITION = 6;
    public const ORGANIZATION_CLIENT = 7;
    public const STATISTICS_ORDER = 8;
    public const VISIT = 9;
    public const ORGANIZATION = 10;

    public static function tableName()
    {
        return 'public.model_type';
    }

    public function rules()
    {
        return [
            [['table_name', 'class', 'name', 'table_alias'], 'required'],
            [['table_name', 'class', 'name', 'table_alias'], 'string']
        ];
    }
}
