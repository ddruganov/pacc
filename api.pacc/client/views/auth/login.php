<?php

/**
 * @var \yii\web\View $this
 * @var \core\validators\LoginValidator $loginValidator
 */

?>

<div class="w-100 m-auto" style="max-width: 375px;">
    <h3 class="mb-1 text-center">Авторизация</h3>
    <form method="post">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        <input id="email" class="form-control mb-1" name="email" pattern="^8[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$" placeholder="89995551122" value="<?= $loginValidator->email ?: '89965290417' ?>" required>
        <div style="font-size: 11px; color: red; padding: 0 0.75rem">
            <?= $loginValidator->getFirstError('email') ?>
        </div>
        <input id="password" class="form-control mb-1" name="password" type="password" required placeholder="Пароль" value="lkpassclient">
        <div style="font-size: 11px; color: red; padding: 0 0.75rem">
            <?= $loginValidator->getFirstError('password') ?>
        </div>
        <button class="btn btn-success btn-block">Войти</button>
    </form>
</div>