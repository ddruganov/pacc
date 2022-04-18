<?php

namespace core\components\exceptions\news;

use Exception;

class ArticleNotFoundException extends Exception
{
    public function __construct(?int $articleId)
    {
        parent::__construct('Статья #' . $articleId . ' не найдена');
    }
}
