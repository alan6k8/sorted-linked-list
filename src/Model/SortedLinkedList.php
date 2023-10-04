<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model;

use Alan6k8\SortedLinkedList\Exception\ItemTypeMismatchException;
use Alan6k8\SortedLinkedList\Model\Item\AbstractItem;
use Alan6k8\SortedLinkedList\Model\Item\IntegerItem;
use Alan6k8\SortedLinkedList\Model\Item\StringItem;

class SortedLinkedList
{
    private ?AbstractItem $head = null;

    public function pushValue(string|int $value): AbstractItem
    {
        $item = is_int($value) ? new IntegerItem($value) : new StringItem($value);
        $this->pushItem($item);

        return $item;
    }

    public function pushStringValue(string $value): StringItem
    {
        $item = new StringItem($value);
        $this->pushItem($item);

        return $item;
    }

    public function pushIntValue(int $value): IntegerItem
    {
        $item = new IntegerItem($value);
        $this->pushItem($item);

        return $item;
    }

    public function pushItem(AbstractItem $item): void
    {
        if ($this->head === null) {
            $this->head = $item;

            return;
        }

        if (!$this->head->isOfSameType($item->getType())) {
            throw new ItemTypeMismatchException('Mixing item types is not allowed');
        }

        // traverse to the end
        $current = $this->head;
        while ($current->getNext() !== null) {
            $current = $current->getNext();
        }

        $current->setNext($item);
    }

    public function unshiftValue(string|int $value): AbstractItem
    {
        $item = is_int($value) ? new IntegerItem($value) : new StringItem($value);
        $this->unshiftItem($item);

        return $item;
    }

    public function unshiftStringValue(string $value): StringItem
    {
        $item = new StringItem($value);
        $this->unshiftItem($item);

        return $item;
    }

    public function unshiftIntValue(int $value): IntegerItem
    {
        $item = new IntegerItem($value);
        $this->unshiftItem($item);

        return $item;
    }

    public function unshiftItem(AbstractItem $item): void
    {
        if ($this->head === null) {
            $this->head = $item;

            return;
        }

        if (!$this->head->isOfSameType($item->getType())) {
            throw new ItemTypeMismatchException('Mixing item types is not allowed');
        }

        $item->setNext($this->head);
        $this->head = $item;
    }

    /**
     * @return string[]|int[]
     */
    public function toArray(): array
    {
        if ($this->head === null) {
            return [];
        }

        $list = [];
        $current = $this->head;
        while ($current->getNext() !== null) {
            $list[] = $current->getValue();
            $current = $current->getNext();
        }
        $list[] = $current->getValue();

        return $list;
    }

    // /**
    //  * @param AbstractItem $item
    //  * @throws ItemTypeMismatchException on failed check
    //  */
    // protected function checkTypeConsistency(AbstractItem $item): void
    // {
    //     if ($this->head === null) {
    //         return;
    //     }

    //     if (!$this->head->isOfSameType($item->getType())) {
    //         throw new ItemTypeMismatchException();
    //     }
    // }
}
