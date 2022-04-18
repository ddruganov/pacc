<?php

/**
 * @var string $email
 * @var string $name
 * @var string $adminLink
 * @var string $verifyEmailLink
 */

?>

<h3>Здравствуйте, <?= $name ?>!</h3>
<div style="margin-bottom: 1rem">
    <span>Вы получили это письмо, потому что зарегистрировались на сайте</span>
    <a href="<?= $adminLink ?>">admin.pacc.com</a>
</div>
<div style="margin-bottom: 2rem">
    <span>Для завершения регистрации пройдите по</span>
    <a href="<?= $verifyEmailLink ?>">этой ссылке</a>
</div>
<div style="margin-bottom: 2rem">
    Если Вы этого не делали, то просто проигнорируйте это письмо.
</div>

<div style="font-size: 0.8rem">
    С уважением, команда pAcc
</div>