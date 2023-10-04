<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model\Item;

class IntegerItem extends AbstractItem
{
    public function __construct(
        int $value,
    ) {
        parent::__construct($value);
    }

    public function getType(): ItemType
    {
        return ItemType::INT;
    }

    public function getValue(): int
    {
        return (int)$this->value;
    }
}
