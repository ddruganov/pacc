<?php

namespace core\email;

use core\components\ExecutionResult;
use core\components\helpers\DateHelper;
use core\models\email\EmailQueue;
use core\models\email\EmailStatus;

class EmailQueueHandler
{
    /** @var EmailReceiverInterface[] */
    private array $receivers;
    /** @var Email[] */
    private array $emails;

    /** @param EmailReceiverInterface[] $receivers */
    public function setReceivers(array $receivers): self
    {
        $this->receivers = $receivers;
        return $this;
    }

    public function addEmail(Email $email): self
    {
        $this->emails[] = $email;
        return $this;
    }

    public function run(): ExecutionResult
    {
        foreach ($this->receivers as $receiver) {
            foreach ($this->emails as $email) {
                $model = new EmailQueue([
                    'creationDate' => DateHelper::now(),
                    'status' => EmailStatus::NEW,
                    'subject' => $email->getSubject(),
                    'content' => $email->render(),
                    'retryCount' => 0,
                    'receiverId' => $receiver->getId(),
                    'receiverModelTypeId' => $receiver->getModelTypeId()
                ]);
                if (!$model->save()) {
                    return new ExecutionResult(false, $model->getFirstErrors());
                }
            }
        }

        return new ExecutionResult(true);
    }
}
