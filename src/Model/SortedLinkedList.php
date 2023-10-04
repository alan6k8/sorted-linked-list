<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model;

use Alan6k8\SortedLinkedList\Exception\ItemTypeMismatchException;
use Alan6k8\SortedLinkedList\Model\Item\AbstractItem;
use Alan6k8\SortedLinkedList\Model\Item\IntegerItem;
use Alan6k8\SortedLinkedList\Model\Item\StringItem;
use Alan6k8\SortedLinkedList\Value\LinkedListSortOrder;

class SortedLinkedList
{
    private ?AbstractItem $head = null;

    public function __construct(
        private readonly LinkedListSortOrder $sortOrder = LinkedListSortOrder::ASC,
    ) {
    }

    public function addValue(string|int $value): AbstractItem
    {
        $item = is_int($value) ? new IntegerItem($value) : new StringItem($value);
        $this->add($item);

        return $item;
    }

    public function addStringValue(string $value): StringItem
    {
        $item = new StringItem($value);
        $this->add($item);

        return $item;
    }

    public function addIntValue(int $value): IntegerItem
    {
        $item = new IntegerItem($value);
        $this->add($item);

        return $item;
    }

    public function add(AbstractItem $item, bool $uniqueOnly = false): void
    {
        if ($this->head === null) {
            $this->head = $item;

            return;
        }

        if (!$this->head->isOfSameType($item->getType())) {
            throw new ItemTypeMismatchException('Mixing item types is not allowed');
        }

        $comparison = $this->compare($this->head, $item);
        if (
            $this->sortOrder === LinkedListSortOrder::ASC && $comparison > 0
            || $this->sortOrder === LinkedListSortOrder::DESC && $comparison < 1
        ) {
            if ($uniqueOnly && $comparison === 0) {
                // value is already enlisted
                return;
            }

            $item->setNext($this->head);
            $this->head = $item;

            return;
        }

        // traverse to the end
        $current = $this->head;
        while ($current->getNext() !== null) {
            $comparison = $this->compare($current->getNext(), $item);
            if (
                $this->sortOrder === LinkedListSortOrder::ASC && $comparison > 0
                || $this->sortOrder === LinkedListSortOrder::DESC && $comparison < 1
            ) {
                if ($uniqueOnly && $comparison === 0) {
                    // value is already enlisted
                    return;
                }

                // add item and terminate iteration
                $item->setNext($current->getNext());
                $current->setNext($item);

                return;
            }
            $current = $current->getNext();
        }

        // passed whole list w/o insertion thus adding at the very end
        $current->setNext($item);
    }

    public function remove(AbstractItem $item): bool
    {
        if ($this->head === null) {
            return false;
        }

        $current = $this->head;
        while ($current->getNext() !== null) {
            $comparison = $current->getNext() === $item;
            if ($comparison === 0) {
                // remove item and terminate iteration
                $next = $current->getNext()->getNext();
                $item->setNext($current->getNext());
                $current->setNext($item);

                return true;
            }
        }

        // todo case missing

        return false;
    }

    public function removeAllByValue(string|int $value): int
    {

    }

    public function removeFirstByValue(string|int $value): bool
    {

    }

    public function pop(): AbstractItem
    {

    }
    public function shift(): AbstractItem
    {

    }

    public function hasValue(string|int $value): bool
    {

    }

    public function hasItem(AbstractItem $item): bool
    {
            // ===
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

    protected function compare(AbstractItem $enlistedItem, AbstractItem $itemToEnlist): int
    {
        return $enlistedItem->getValue() <=> $itemToEnlist->getValue();
    }
}
