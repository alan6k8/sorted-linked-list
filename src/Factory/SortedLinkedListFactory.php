<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Factory;

use Alan6k8\SortedLinkedList\Model\SortedLinkedList;
use Alan6k8\SortedLinkedList\Value\LinkedListSortOrder;

class SortedLinkedListFactory
{
    public function create(
        ?LinkedListSortOrder $sortOrder = null,
    ): SortedLinkedList {
        return $sortOrder === null
            ? new SortedLinkedList()
            : new SortedLinkedList($sortOrder);
    }

    public function createAscending(): SortedLinkedList
    {
        return new SortedLinkedList(LinkedListSortOrder::ASC);
    }

    public function createDescending(): SortedLinkedList
    {
        return new SortedLinkedList(LinkedListSortOrder::DESC);
    }
}
