<?php

/* @var $this \yii\web\View */
/* @var $content string */

use client\assets\AppAsset;
use core\components\helpers\ClientHelper;
use core\models\organization\OrganizationClient;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<style>
    .wrap {
        min-height: 100vh;
    }
</style>

<body class="h-100">
    <?php $this->beginBody() ?>

    <div class="wrap h-100">

        <?php if (ClientHelper::id()) : ?>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand d-flex align-items-center" style="font-size: 24px" href="/">
                    <?= ClientHelper::get()->name ?>
                </a>
                <ul class="navbar-nav">
                    <li>
                        <form action="/settings/setDefaultOrganizationId">
                            <input type="hidden" name="backurl" value="<?= Yii::$app->request->url ?>">
                            <select name="organizationId">
                                <?php foreach (OrganizationClient::findAll(['client_id' => ClientHelper::id()]) as $oc) : ?>
                                    <option value="<?= $oc->getOrganization()->id ?>" <?= ClientHelper::getDefaultOrganizationId() === $oc->getOrganization()->id ? 'selected="true"' : '' ?>>
                                        <?= $oc->getOrganization()->name ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </form>
                    </li>
                    <li class="ml-3">
                        <a href="/pass-instance">Мои абонементы</a>
                    </li>
                    <li class="ml-3">
                        <a href="/settings">Настройки</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li>
                        <a href="/auth/logout">Выход</a>
                    </li>
                </ul>
            </nav>
        <?php endif ?>

        <div class="container d-flex h-100">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>