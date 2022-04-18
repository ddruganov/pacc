<?php

namespace core\email;

use Exception;
use Yii;

class Email
{
    protected string $templatePath;
    protected array $params;

    public function render(): string
    {
        ob_start();

        extract($this->params);
        include $this->templatePath;
        $content = ob_get_contents();

        ob_end_clean();

        ob_start();

        extract(['content' => $content]);
        include Yii::getAlias('@core/email/templates/main.php');
        $fullRender = ob_get_contents();

        ob_end_clean();

        return $fullRender;
    }

    public function getSubject(): string
    {
        throw new Exception('Not implemented');
    }
}
