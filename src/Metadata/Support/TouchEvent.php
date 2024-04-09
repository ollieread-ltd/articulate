<?php
declare(strict_types=1);

namespace Articulate\Metadata\Support;

enum TouchEvent
{
    case Created;

    case Updated;

    case Related;
}
