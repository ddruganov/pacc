<?php

namespace client\controllers;

use client\controllers\actions\settings\IndexAction;
use client\controllers\actions\settings\SetDefaultOrganizationIdAction;
use yii\web\Controller;

class SettingsController extends Controller
{
    public function actions()
    {
        return [
            'index' => IndexAction::class,
            'setDefaultOrganizationId' => SetDefaultOrganizationIdAction::class
        ];
    }
}
