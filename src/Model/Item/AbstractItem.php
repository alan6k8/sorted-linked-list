<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model\Item;

abstract class AbstractItem
{
    private ?AbstractItem $next = null;

    public function __construct(
        protected readonly string|int $value,
    ) {
    }

    public function getNext(): ?AbstractItem
    {
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

    abstract public function getType(): ItemType;

    abstract public function getValue(): string|int;
}
