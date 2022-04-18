<?php

namespace api\controllers;

use api\controllers\actions\settings\GetOrganizationInfoAction;
use api\controllers\actions\settings\GetTokensAction;
use api\controllers\actions\settings\RemoveTokenAction;
use api\controllers\actions\settings\SavePasswordAction;
use api\controllers\actions\settings\SetDefaultOrganizationIdAction;
use core\actions\generic\SaveAction;
use core\models\organization\Organization;
use yii\web\Controller;

class SettingsController extends Controller
{
    public function actions()
    {
        return [
            'savePassword' => SavePasswordAction::class,

            'getTokens' => GetTokensAction::class,
            'removeToken' => RemoveTokenAction::class,

            'setDefaultOrganizationId' => SetDefaultOrganizationIdAction::class,
            'getOrganizationInfo' => GetOrganizationInfoAction::class,
            'saveOrganization' => [
                'class' => SaveAction::class,
                'modelClass' => Organization::class
            ]
        ];
    }
}
