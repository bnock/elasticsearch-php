<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum DecayFunctionType: string
{
    case Linear = 'linear';
    case Exponential = 'exp';
    case Gaussian = 'gauss';
}
