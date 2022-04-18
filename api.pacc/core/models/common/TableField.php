<?php

namespace core\models\common;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "public.table_field".
 *
 * @property int $id
 * @property int $modelTypeId
 * @property string $field
 */
class TableField extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'public.table_field';
    }

    public function rules()
    {
        return [
            [['id', 'model_type_id', 'field'], 'required'],
            [['id', 'model_type_id'], 'string'],
            [['field'], 'string']
        ];
    }
}
