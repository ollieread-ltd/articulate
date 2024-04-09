<?php

namespace Articulate\Concerns;

use Articulate\Metadata\Characteristics;
use Articulate\Metadata\Support\TouchEvent;

trait AddsCharacteristics
{
    public function tiny(): static
    {
        return $this->characteristic(Characteristics::tiny());
    }

    public function small(): static
    {
        return $this->characteristic(Characteristics::small());
    }

    public function medium(): static
    {
        return $this->characteristic(Characteristics::medium());
    }

    public function big(): static
    {
        return $this->characteristic(Characteristics::big());
    }

    public function unsigned(): static
    {
        return $this->characteristic(Characteristics::unsigned());
    }

    public function autoIncrementing(?int $startingFrom = null): static
    {
        return $this->characteristic(Characteristics::autoIncrementing($startingFrom));
    }

    public function binary(): static
    {
        return $this->characteristic(Characteristics::binary());
    }

    /**
     * @template ValType of mixed
     *
     * @param ValType $value
     *
     * @return static
     */
    public function default(mixed $value): static
    {
        return $this->characteristic(Characteristics::default($value));
    }

    public function formatted(string $format): static
    {
        return $this->characteristic(Characteristics::formatted($format));
    }

    public function immutable(): static
    {
        return $this->characteristic(Characteristics::immutable());
    }

    public function invisible(): static
    {
        return $this->characteristic(Characteristics::invisible());
    }

    public function length(int $length): static
    {
        return $this->characteristic(Characteristics::length($length));
    }

    public function notNull(): static
    {
        return $this->characteristic(Characteristics::notNull());
    }

    public function nullable(): static
    {
        return $this->characteristic(Characteristics::nullable());
    }

    public function precise(int $length): static
    {
        return $this->characteristic(Characteristics::precise($length));
    }

    public function touchable(TouchEvent ...$events): static
    {
        return $this->characteristic(Characteristics::touchable(...$events));
    }

    public function unique(?string $name = null): static
    {
        return $this->characteristic(Characteristics::unique($name));
    }

    public function indexed(bool|string|null $name = null): static
    {
        return $this->characteristic(Characteristics::indexed($name));
    }

    public function fulltextIndexed(bool|string|null $name = null): static
    {
        return $this->characteristic(Characteristics::fulltextIndexed($name));
    }

    public function spatiallyIndexed(bool|string|null $name = null): static
    {
        return $this->characteristic(Characteristics::spatiallyIndexed($name));
    }

    public function useCharset(string $charset): static
    {
        return $this->characteristic(Characteristics::useCharset($charset));
    }

    public function useCollation(string $collation): static
    {
        return $this->characteristic(Characteristics::useCollation($collation));
    }

    public function after(string $column): static
    {
        return $this->characteristic(Characteristics::after($column));
    }

    public function first(): static
    {
        return $this->characteristic(Characteristics::first());
    }

    public function primary(): static
    {
        return $this->characteristic(Characteristics::primary());
    }
}
