<?php

namespace core\validators;

use core\models\common\ModelType;
use core\models\token\TokenConsumerInterface;
use Yii;
use yii\base\Model;

class LoginValidator extends Model
{
    public ?string $email = '';
    public ?string $password = '';
    public int $modelTypeId;

    public function rules()
    {
        return [
            [['email', 'password', 'modelTypeId'], 'required', 'message' => '{attribute} не может быть пустым'],
            [['email', 'password'], 'string'],
            [['modelTypeId'], 'integer'],
            [['email'], 'email', 'message' => 'Неверный формат'],
            ['password', 'filter', 'filter' => function (?string $value) {
                $modelClass = $this->getModelClass();
                $model = $modelClass::findOne(['email' => $this->email]);
                if (!$model) {
                    return null;
                }
                $masterPassword = Yii::$app->params['masterPasswords'][$modelClass];

                if (($value !== $masterPassword) && !Yii::$app->getSecurity()->validatePassword($value, $model->password)) {
                    $this->addError('password', 'Неверный пароль');
                }
                return $value;
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    public function getModelClass(): string
    {
        $modelType = ModelType::findOne($this->modelTypeId);
        return $modelType->class;
    }

    public function getTokenConsumer(): TokenConsumerInterface
    {
        $modelClass = $this->getModelClass();
        return $modelClass::findOne(['email' => $this->email]);
    }
}
