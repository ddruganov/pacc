<?php

/**
 * @var \yii\web\View $this
 * @var \client\components\validators\ClientSettingsSaver $clientSettingsSaver
 */

?>

<div class="w-100 my-5 d-flex flex-column">

    <h3>Настройки</h3>

    <form method="post" class="d-flex flex-column align-items-start" style="max-width: 500px;">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />

        <div class="form-group w-100 border rounded p-3">
            <h6>Смена пароля</h6>
            <input class="form-control" name="newPassword" type="password" required placeholder="Новый пароль">
            <div style="font-size: 11px; color: red; padding: 0 0.75rem" class="mb-1">
                <?= $clientSettingsSaver->getFirstError('newPassword') ?>
            </div>

            <input class="form-control mb-1" name="repeatNewPassword" type="password" required placeholder="Повторите новый пароль">
            <div style="font-size: 11px; color: red; padding: 0 0.75rem" class="mb-1">
                <?= $clientSettingsSaver->getFirstError('repeatNewPassword') ?>
            </div>
        </div>

        <button class="btn btn-success btn-block">Сохранить</button>
    </form>

</div>