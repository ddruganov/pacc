<?php

namespace client\components;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Yii;

class QRCodeGenerator
{
    public function run(string $data)
    {
        $cacheKey = base64_encode($data);

        $cacheValue = Yii::$app->cache->get($cacheKey);

        if (!$cacheValue) {
            $qrOptions = new QROptions();
            $qrOptions->addQuietzone = false;
            $cacheValue = (new QRCode($qrOptions))->render($data);
            Yii::$app->cache->set($cacheKey, $cacheValue);
        }

        return Yii::$app->cache->get($cacheKey);
    }
}
