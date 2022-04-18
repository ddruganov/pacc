<?php

/**
 * @var \yii\web\View $this
 * @var \core\models\pass\PassInstance[] $passInstances
 * @var bool $unexpiredOnly
 */

use client\components\QRCodeGenerator;
use yii\helpers\Url;

?>

<div class="w-100 my-5 d-flex flex-column">

    <form method="post" class="d-flex flex-column align-items-start">
        <div class="text-dark">
            <span>Показываются</span>
            <button class="expired-only" name="unexpiredOnly" value="<?= $unexpiredOnly ?>"><?= $unexpiredOnly ? 'только действительные' : 'все привязанные' ?></button>
            <span>абонементы</span>
        </div>
    </form>

    <div class="d-flex flex-wrap">
        <?php if ($passInstances) : ?>
            <?php foreach ($passInstances as $passInstance) : ?>
                <div class="card m-3" style="width: 15rem;">
                    <img class="card-img-top" src="<?= (new QRCodeGenerator())->run(Yii::$app->params['hosts']['admin'] . Url::to(['client/visit', 'passInstanceId' => $passInstance['id']])) ?>">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title mb-0">[#<?= $passInstance['id'] ?>] <?= $passInstance['name'] ?></h5>
                        <div class="mb-3">
                            <?php if ($passInstance['lastVisitDate']) : ?>
                                <p class="card-text mb-0">Последнее посещение: <?= $passInstance['lastVisitDate'] ?></p>
                            <?php endif ?>
                            <p class="card-text">Осталось посещений: <?= $passInstance['hoursLeft'] ?></p>
                        </div>
                        <a href="<?= Url::to(['passInstance/visits', 'id' => $passInstance['id']]) ?>" class="btn btn-primary btn-block">Посещения</a>
                    </div>
                </div>
            <?php endforeach ?>
        <?php else : ?>
            <h3 class="mt-3">
                <div>У Вас нет <?= $unexpiredOnly ? 'действительных' : ''  ?> абонементов.</div>
                <div>Обратитесь к администратору для покупки.</div>
            </h3>
        <?php endif ?>
    </div>

</div>