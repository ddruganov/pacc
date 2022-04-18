<?php

namespace core\email;

class EmailSender
{
    /** @var EmailReceiverInterface[] */
    private array $receivers;
    private string $emailContent;

    /** @param EmailReceiverInterface[] $receivers */
    public function setReceivers(array $receivers): self
    {
        $this->receivers = $receivers;
        return $this;
    }

    public function setEmailContent(string $emailContent): self
    {
        $this->emailContent = $emailContent;
        return $this;
    }

    public function send()
    {
    }
}
