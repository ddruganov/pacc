<?php

namespace api\controllers;

use api\controllers\actions\image\UploadAction;
use yii\web\Controller;

class ImageController extends Controller
{
    public function actions()
    {
        return [
            'upload' => UploadAction::class
        ];
    }
}
