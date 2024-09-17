<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum DistanceUnit: string
{
    case Mile = 'mi';
    case Yard = 'yd';
    case Feet = 'ft';
    case Inch = 'in';
    case Kilometer = 'km';
    case Meter = 'm';
    case Centimeter = 'cm';
    case Millimeter = 'mm';
    case NauticalMile = 'nmi';
}
