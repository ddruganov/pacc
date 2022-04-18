<?php

namespace api\controllers;

use core\actions\generic\CollectorAction;
use core\actions\generic\DeleteAction;
use core\actions\generic\SaveAction;
use core\collectors\calendar\CalendarEntryCollector;
use core\collectors\calendar\OneCalendarEntryCollector;
use core\collectors\calendar\BoundModelAutocompleteCollector;
use core\models\calendar\CalendarEntry;
use yii\web\Controller;

class CalendarController extends Controller
{
    public function actions()
    {
        return [
            'getEntries' => [
                'class' => CollectorAction::class,
                'collectorClass' => CalendarEntryCollector::class
            ],
            'getEntryInfo' => [
                'class' => CollectorAction::class,
                'collectorClass' => OneCalendarEntryCollector::class
            ],
            'saveEntry' => [
                'class' => SaveAction::class,
                'modelClass' => CalendarEntry::class
            ],
            'deleteEntry' => [
                'class' => DeleteAction::class,
                'modelClass' => CalendarEntry::class
            ],
            'autocomplete' => [
                'class' => CollectorAction::class,
                'collectorClass' => BoundModelAutocompleteCollector::class
            ]
        ];
    }
}
