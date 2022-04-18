<?php

namespace client\components\validators;

use core\components\helpers\ClientHelper;
use Yii;
use yii\base\Model;

class ClientSettingsSaver extends Model
{
    public ?string $newPassword = null;
    public ?string $repeatNewPassword = null;

    public function rules()
    {
        return [
            [['newPassword', 'repeatNewPassword'], 'required', 'message' => '{attribute} не может быть пустым'],
            [['newPassword', 'repeatNewPassword'], 'string'],
            ['newPassword', 'filter', 'filter' => function (?string $value) {
                if (strlen($value) < 6) {
                    $this->addError('newPassword', 'Пароль не может быть короче 6 символов');
                }
                return $value;
            }],
            ['repeatNewPassword', 'filter', 'filter' => function (?string $value) {
                if (strcmp($this->newPassword, $this->repeatNewPassword)) {
                    $this->addError('repeatNewPassword', 'Пароли не совпадают');
                }
                return $value;
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $client = ClientHelper::get();
        $client->password = Yii::$app->security->generatePasswordHash($this->newPassword);

        return $client->save();
    }
}
