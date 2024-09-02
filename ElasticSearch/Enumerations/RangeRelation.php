<?php

namespace App\Services\ElasticSearch\Enumerations;

enum RangeRelation: string
{
    case Intersects = 'intersects';
    case Contains = 'contains';
    case Within = 'within';
}
