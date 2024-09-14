<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum ScoreMode: string
{
    case Multiply = 'multiply';
    case Sum = 'sum';
    case Average = 'avg';
    case First = 'first';
    case Maximum = 'max';
    case Minimum = 'min';
}
