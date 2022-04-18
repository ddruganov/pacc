<?php

namespace client\controllers;

use client\controllers\actions\passInstance\IndexAction;
use yii\web\Controller;

class PassInstanceController extends Controller
{
    public function actions()
    {
        return [
            'index' => IndexAction::class,
        ];
    }
}
