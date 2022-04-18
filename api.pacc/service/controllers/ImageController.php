<?php

namespace service\controllers;

use service\controllers\actions\image\UploadAction;
use yii\web\Controller;

class ImageController extends Controller
{
    public function actions()
    {
        return [
            'upload' => UploadAction::class,
        ];
    }
}
