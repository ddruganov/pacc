<?php

namespace api\controllers\actions\image;

use core\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use core\models\service\Image;
use Exception;
use Throwable;
use Yii;

class UploadAction extends ApiAction
{
    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!isset($_FILES['image'])) {
                throw new Exception('Изображение отсутствует');
            }

            $uploadedImage = $_FILES['image'];

            if (!in_array(strtolower($uploadedImage['type']), ['image/jpeg', 'image/png'])) {
                throw new Exception('Неверный формат изображения');
            }

            // check size
            if ($uploadedImage['size'] > Yii::$app->params['imageupload']['maxSize']) {
                throw new Exception('Размер изображения превышает допустимые значения');
            }

            // check dimensions
            $imageSize = getimagesize($uploadedImage['tmp_name']);
            if ($imageSize[0] > Yii::$app->params['imageupload']['maxWidth']) {
                throw new Exception('Изображение не может быть шире ' . Yii::$app->params['imageupload']['maxWidth'] . ' пикселей');
            }
            if ($imageSize[1] > Yii::$app->params['imageupload']['maxHeight']) {
                throw new Exception('Изображение не может быть выше ' . Yii::$app->params['imageupload']['maxHeight'] . ' пикселей');
            }

            // save image info
            $imageAR = new Image([
                'organizationId' => UserHelper::getOrganizationId(),
                'creationDate' => date('Y/m/d'),
                'extension' => pathinfo($uploadedImage['name'], PATHINFO_EXTENSION),
                'hash' => md5_file($uploadedImage['tmp_name']),
                'size' => $uploadedImage['size']
            ]);
            if (!$imageAR->save()) {
                throw new Exception(@reset($imageAR->getFirstErrors()));
            }

            // save image to disk
            if (!file_exists($imageAR->getDirectory())) {
                mkdir($imageAR->getDirectory(), 0777, true);
            }
            if (!file_put_contents($imageAR->getFilepath(), file_get_contents($uploadedImage['tmp_name']))) {
                throw new Exception('Не удалось сохранить изображение на диск');
            }

            $transaction->commit();
            return $this->apiResponse(new ExecutionResult(true, [], ['id' => $imageAR->id, 'url' => $imageAR->getUrl()]));
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
