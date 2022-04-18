<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\DeleteAction;
use core\actions\generic\SaveAction;
use core\actions\generic\ToggleAction;
use core\collectors\news\ArticleOneCollector;
use core\collectors\news\ArticleAllCollector;
use core\models\news\Article;
use yii\web\Controller;

class NewsController extends Controller
{
    public function actions()
    {
        return [
            'getAll' => [
                'class' => CollectorAction::class,
                'collectorClass' => ArticleAllCollector::class
            ],
            'getOne' => [
                'class' => CollectorAction::class,
                'collectorClass' => ArticleOneCollector::class
            ],
            'toggle' => [
                'class' => ToggleAction::class,
                'modelClass' => Article::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Article::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Article::class
            ]
        ];
    }
}
