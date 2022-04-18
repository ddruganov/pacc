<?php

namespace core\components\statistics;

use core\components\statistics\modifiers\AbstractStatisticsViewQueryModifier;
use core\components\statistics\modifiers\BetweenModifier;
use core\components\statistics\modifiers\EqualModifier;
use core\components\statistics\modifiers\GreaterThanModifier;
use core\components\statistics\modifiers\GreaterThanOrEqualModifier;
use core\components\statistics\modifiers\IlikeModifier;
use core\components\statistics\modifiers\LessThanModifier;
use core\components\statistics\modifiers\LessThanOrEqualModifier;
use core\models\statistics\StatisticsConditionType;
use Exception;

class StatisticsViewQueryModifierFactory
{
    public static function get(int $conditionTypeId): AbstractStatisticsViewQueryModifier
    {
        switch ($conditionTypeId) {
            case StatisticsConditionType::EQUAL:
                return new EqualModifier();
            case StatisticsConditionType::LESS_THAN:
                return new LessThanModifier();
            case StatisticsConditionType::LESS_THAN_OR_EQUAL:
                return new LessThanOrEqualModifier();
            case StatisticsConditionType::GREATER_THAN:
                return new GreaterThanModifier();
            case StatisticsConditionType::GREATER_THAN_OR_EQUAL:
                return new GreaterThanOrEqualModifier();
            case StatisticsConditionType::ILIKE:
                return new IlikeModifier();
            case StatisticsConditionType::BETWEEN:
                return new BetweenModifier();
            default:
                throw new Exception('Unknown condition type id');
        }
    }
}
