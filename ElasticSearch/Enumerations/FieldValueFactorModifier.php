<?php

namespace App\Services\ElasticSearch\Enumerations;

enum FieldValueFactorModifier: string
{
    case None = 'none';
    case CommonLogarithm = 'log';
    case CommonLogarithmPlus1 = 'log1p';
    case CommonLogarithmPlus2 = 'log2p';
    case NaturalLogarithm = 'ln';
    case NaturalLogarithmPlus1 = 'ln1p';
    case NaturalLogarithmPlus2 = 'ln2p';
    case Square = 'square';
    case SquareRoot = 'sqrt';
    case Reciprocal = 'reciprocal';
}
