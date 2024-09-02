<?php

namespace App\Services\ElasticSearch\Enumerations;

enum SortDirection: string
{
    case Ascending = 'asc';
    case Descending = 'desc';
}
