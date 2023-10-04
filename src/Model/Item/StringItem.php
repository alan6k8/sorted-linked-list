<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model\Item;

class StringItem extends AbstractItem
{
    public function __construct(
        string $value,
    ) {
        parent::__construct($value);
    }

    public function getType(): ItemType
    {
        return ItemType::STRING;
    }

    public function getValue(): string
    {
        return (string)$this->value;
    }
}
