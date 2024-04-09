<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;
use Articulate\Metadata\Support\TouchEvent;

/**
 *
 */
final readonly class Touchable implements FieldCharacteristic
{
    /**
     * @var \Articulate\Metadata\Support\TouchEvent[]
     */
    public array $touchEvents;

    /**
     * @param \Articulate\Metadata\Support\TouchEvent[] $touchEvents
     */
    public function __construct(array $touchEvents)
    {
        $this->touchEvents = $touchEvents;
    }

    /**
     * @param \Articulate\Metadata\Support\TouchEvent $touchEvent
     *
     * @return bool
     */
    public function on(TouchEvent $touchEvent): bool
    {
        return in_array($touchEvent, $this->touchEvents, true);
    }
}
