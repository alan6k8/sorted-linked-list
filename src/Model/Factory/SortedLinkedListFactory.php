<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Model\Factory;

use Alan6k8\SortedLinkedList\Model\SortedLinkedList;

class SortedLinkedListFactory
{
    public function create(): SortedLinkedList
    {
        return new SortedLinkedList();
    }
}
