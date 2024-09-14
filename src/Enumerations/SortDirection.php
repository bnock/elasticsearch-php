<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum SortDirection: string
{
    case Ascending = 'asc';
    case Descending = 'desc';
}
