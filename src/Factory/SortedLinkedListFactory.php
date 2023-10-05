<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Factory;

use Alan6k8\SortedLinkedList\Model\SortedLinkedList;
use Alan6k8\SortedLinkedList\Value\LinkedListSortOrder;

class SortedLinkedListFactory
{
    public function create(
        LinkedListSortOrder $sortOrder = LinkedListSortOrder::ASC,
        bool $uniqueValuesOnly = false,
    ): SortedLinkedList {
        return new SortedLinkedList($sortOrder, $uniqueValuesOnly);
    }

    public function createAscending(bool $uniqueValuesOnly = false): SortedLinkedList
    {
        return new SortedLinkedList(LinkedListSortOrder::ASC, $uniqueValuesOnly);
    }

    public function createDescending(bool $uniqueValuesOnly = false): SortedLinkedList
    {
        return new SortedLinkedList(LinkedListSortOrder::DESC, $uniqueValuesOnly);
    }
}
