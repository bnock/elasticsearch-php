<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum MultiMatchOperator: string
{
    case And = 'and';
    case Or = 'or';
}
