<?php

namespace App\Services\ElasticSearch\Enumerations;

enum MultiMatchOperator: string
{
    case And = 'and';
    case Or = 'or';
}
