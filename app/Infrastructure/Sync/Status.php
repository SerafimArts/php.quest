<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

enum Status
{
    case DOWNLOAD;
    case EXTRACT;
    case COPY;
    case UPDATE;
}
