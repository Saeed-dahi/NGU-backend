<?php

namespace App\Enum\Inventory;

enum ProductType: String
{
    case COMMERCIAL = 'commercial';
    case FINISHED = 'finished';
    case RAW = 'raw';
    case ASSEMBLY = 'assembly';
    case RUNNING = 'running';
    case SEMI_FINISHED = 'semi_finished';
    case SPARE_PARTS = 'spare_parts';
    case PRODUCTION_REQUIREMENTS = 'production_requirements';
    case SERVICE = 'service';
}
