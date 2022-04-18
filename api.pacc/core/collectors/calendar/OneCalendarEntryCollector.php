<?php

namespace core\collectors\calendar;

use core\collectors\AbstractDataCollector;
use core\models\calendar\CalendarEntry;

class OneCalendarEntryCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $entry = CalendarEntry::findOne($this->getParam('id'));
        $boundModel = $entry->getBoundModel();
        return $boundModel->getDataForCalendar();
    }
}
