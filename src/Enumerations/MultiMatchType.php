<?php

namespace BNock\ElasticsearchPHP\Enumerations;

enum MultiMatchType: string
{
    case BestFields = 'best_fields';
    case MostFields = 'most_fields';
    case CrossFields = 'cross_fields';
    case Phrase = 'phrase';
    case PhrasePrefix = 'phrase_prefix';
    case BoolPrefix = 'bool_prefix';
}
