<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum GeoShapeRelation: string
{
    case Intersects = 'intersects';
    case Disjoint = 'disjoint';
    case Within = 'within';
    case Contains = 'contains';
}
