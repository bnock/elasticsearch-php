<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum RangeRelation: string
{
    case Intersects = 'intersects';
    case Contains = 'contains';
    case Within = 'within';
}
