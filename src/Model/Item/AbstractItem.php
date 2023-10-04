<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model\Item;

use UnexpectedValueException;

abstract class AbstractItem
{
    private ?AbstractItem $next = null;

    public function __construct(
        protected readonly string|int $value,
    ) {
    }

    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    public function getNext(): AbstractItem
    {
        if ($this->next === null) {
            throw new UnexpectedValueException('This item does not point to next item');
        }

        return $this->next;
    }

    public function setNext(?AbstractItem $next): void
    {
        $this->next = $next;
    }

    public function isOfSameType(ItemType $itemType): bool
    {
        return $this->getType() === $itemType;
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }

    abstract public function getType(): ItemType;

    abstract public function getValue(): string|int;
}
