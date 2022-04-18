<?php

namespace core\collectors\news;

use core\collectors\AbstractDataCollector;
use core\components\exceptions\news\ArticleNotFoundException;
use core\models\news\Article;
use yii\web\ForbiddenHttpException;

class ArticleOneCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $articleId = $this->getParam('id');
        $article = Article::findOne($articleId);
        if (!$article) {
            throw new ArticleNotFoundException($articleId);
        }
        $organizationId = $this->getParam('organizationId');
        if ($article->organizationId !== $organizationId) {
            throw new ForbiddenHttpException('Статья #' . $articleId . ' не принадлежит организации #' . $organizationId);
        }

        return $article->getAttributes(['id', 'creationDate', 'showAfterDate', 'showBeforeDate', 'active', 'title', 'contents']);
    }
}
