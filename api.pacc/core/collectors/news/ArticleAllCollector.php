<?php

namespace core\collectors\news;

use core\collectors\PagedDataCollector;
use core\models\news\Article;
use yii\db\Query;

class ArticleAllCollector extends PagedDataCollector
{
    public function get(): array
    {
        $this->query = (new Query())
            ->select([
                'id',
                'creation_date',
                'show_after_date',
                'show_before_date',
                'active',
                'title' => 'coalesce(title, \'Статья без названия\')',
                'contents'
            ])
            ->from(Article::tableName())
            ->andWhere(['organization_id' => $this->getParam('organizationId')]);

        // show after date
        if ($showAfterDate = $this->getParam('filter.showAfterDate.from')) {
            $this->query->andWhere(['>=', 'show_after_date', $showAfterDate]);
        }
        // show before date
        if ($showBeforeDate = $this->getParam('filter.showBeforeDate.to')) {
            $this->query->andWhere(['<=', 'show_before_date', $showBeforeDate]);
        }

        $this->query->orderBy(['id' => SORT_DESC]);

        $pageCount = $this->getPageCount();

        if ($page = $this->getParam('page')) {
            $this->setPage($page);
        }

        $articles = $this->query->all();

        foreach ($articles as $key => $article) {
            $articles[$key]['creationDate'] = date('d.m.Y', strtotime($article['creation_date']));
            $articles[$key]['showAfterDate'] = $article['show_after_date'] ? date('d.m.Y', strtotime($article['show_after_date'])) : null;
            $articles[$key]['showBeforeDate'] = $article['show_before_date'] ? date('d.m.Y', strtotime($article['show_before_date'])) : null;
            unset($articles[$key]['creation_date'], $articles[$key]['show_after_date'], $articles[$key]['show_before_date']);
        }

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'models' => $articles
        ];
    }
}
