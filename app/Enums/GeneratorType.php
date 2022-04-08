<?php

namespace App\Enums;

enum GeneratorType: string
{
    case ALL = 'all';
    case ONLY_MODEL_AND_MIGRATION = 'only model & migration';
}
