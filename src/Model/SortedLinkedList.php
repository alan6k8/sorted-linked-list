<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model;

use Alan6k8\SortedLinkedList\Exception\ItemTypeMismatchException;
use Alan6k8\SortedLinkedList\Model\Item\AbstractItem;
use Alan6k8\SortedLinkedList\Model\Item\IntegerItem;
use Alan6k8\SortedLinkedList\Model\Item\StringItem;
use Alan6k8\SortedLinkedList\Value\LinkedListSortOrder;
use Generator;
use RuntimeException;
use UnexpectedValueException;

class SortedLinkedList
{
    private ?AbstractItem $head = null;

    /**
     * @param LinkedListSortOrder $sortOrder
     * @param bool $uniqueValuesOnly tells if the list should only contain items holding unique values
     */
    public function __construct(
        protected readonly LinkedListSortOrder $sortOrder,
        protected readonly bool $uniqueValuesOnly,
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

    public function add(AbstractItem $item): void
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
            $this->sortOrder === LinkedListSortOrder::ASC && $comparison >= 0
            || $this->sortOrder === LinkedListSortOrder::DESC && $comparison < 1
        ) {
            if ($this->uniqueValuesOnly && $comparison === 0) {
                // value is already enlisted
                return;
            }

            $item->setNext($this->head);
            $this->head = $item;

            return;
        }

        // traverse to the end
        $current = $this->head;
        while ($current->hasNext()) {
            $comparison = $this->compare($current->getNext(), $item);
            if (
                $this->sortOrder === LinkedListSortOrder::ASC && $comparison >= 0
                || $this->sortOrder === LinkedListSortOrder::DESC && $comparison < 1
            ) {
                if ($this->uniqueValuesOnly && $comparison === 0) {
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

        if ($this->head === $item) {
            $shifted = $this->shift();
            unset($shifted);    // allow GC to collect it

            return true;
        }

        $current = $this->head;
        while ($current->hasNext()) {
            if ($current->getNext() !== $item) {
                continue;
            }

            // remove item and amend item link
            $removedItem = $current->getNext();
            $nextOfRemoved = $removedItem->hasNext() ? $removedItem->getNext() : null;
            $removedItem->setNext(null); // remove deleted item from chain
            $current->setNext($nextOfRemoved);
            unset($removedItem);    // allow GC to collect it

            return true;
        }

        return false;
    }

    // public function removeAllByValue(string|int $value): int
    // {
        // just an idea
        // would most likely get powered by iteration and self::removeFirstByValue()
    // }

    // public function removeFirstByValue(string|int $value): bool
    // {
        // just an idea, could be powered by self::remove() but that would need AbstractItem::isEqualTo(AbstractItem $item, bool $strict)
        // where strict would use === operator while !strict would go w/ value comparison
    // }

    public function pop(): AbstractItem
    {
        if ($this->head === null) {
            throw new UnexpectedValueException('Cannot pop from empty list');
        }

        if (!$this->head->hasNext()) {
            $item = $this->head;
            $this->head = null;

            return $item;
        }

        $current = $this->head;
        while ($current->hasNext()) {
            if (!$current->getNext()->hasNext()) {
                // pop item and terminate iteration
                $item = $current->getNext();
                $current->setNext(null);

                return $item;
            }
            $current = $current->getNext();
        }

        throw new RuntimeException('Pop failed most likely due to implementation issue');
    }

    public function shift(): AbstractItem
    {
        if ($this->head === null) {
            throw new UnexpectedValueException('Cannot shift from empty list');
        }

        $item = $this->head;
        if ($this->head->hasNext()) {
            $this->head = $this->head->getNext();
        } else {
            $this->head = null;
        }
        // remove item from list
        $item->setNext(null);

        return $item;
    }

    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    public function hasValue(string|int $value): bool
    {
        foreach ($this->iterate() as $enlistedItem) {
            if ($value === $enlistedItem->getValue()) {
                return true;
            }
        }

        return false;
    }

    public function hasItem(AbstractItem $item): bool
    {
        foreach ($this->iterate() as $enlistedItem) {
            if ($item === $enlistedItem) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Generator<int, AbstractItem, AbstractItem, void>
     */
    public function iterate(): Generator
    {
        if ($this->head === null) {
            return;
        }

        $current = $this->head;
        while ($current->hasNext()) {
            yield $current;
            $current = $current->getNext();
        }
        yield $current;
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
        while ($current->hasNext()) {
            $list[] = $current->getValue();
            $current = $current->getNext();
        }
        $list[] = $current->getValue();

        return $list;
    }

    protected function compare(AbstractItem $enlistedItem, AbstractItem $itemToEnlist): int
    {
        return $enlistedItem->getValue() <=> $itemToEnlist->getValue();
        // fwrite(STDERR, $enlistedItem->getValue() . ' vs ' . $itemToEnlist->getValue() . ' = ' . $ret. PHP_EOL);
        // return  $ret;
    }
}
