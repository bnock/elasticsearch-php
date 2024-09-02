<?php

namespace App\Services\ElasticSearch\Enumerations;

enum DecayFunctionType: string
{
    case Linear = 'linear';
    case Exponential = 'exp';
    case Gaussian = 'gauss';
}
