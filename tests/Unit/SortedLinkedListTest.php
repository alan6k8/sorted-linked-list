<?php

declare(strict_types=1);

namespace Tests\Unit;

use Alan6k8\SortedLinkedList\Exception\ItemTypeMismatchException;
use Alan6k8\SortedLinkedList\Factory\SortedLinkedListFactory;
use Alan6k8\SortedLinkedList\Model\Item\IntegerItem;
use Alan6k8\SortedLinkedList\Model\Item\StringItem;
use Alan6k8\SortedLinkedList\Model\SortedLinkedList;
use Codeception\AssertThrows;
use Codeception\Util\Debug;
use Tests\Support\UnitTester;

class SortedLinkedListTest extends \Codeception\Test\Unit
{
    use AssertThrows;

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testAddItemDescendingStrings()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createDescending();
        $list->add(new StringItem('Bladnoch'));
        Debug::debug($list->toArray());

        // prepend to the beginning
        $list->addStringValue('Machrie Moor');
        Debug::debug($list->toArray());

        // append to the end
        $list->add(new StringItem('Ardbeg Corryvreckan'));
        Debug::debug($list->toArray());

        // insert in the middle
        $list->add(new StringItem('Cragganmore'));
        Debug::debug($list->toArray());
        $list->add(new StringItem('Macallan'));
        Debug::debug($list->toArray());

        // item gets pushed to the end of the list
        $this->assertEquals(
            ['Machrie Moor', 'Macallan', 'Cragganmore', 'Bladnoch', 'Ardbeg Corryvreckan'],
            $list->toArray(),
            'List should contain expected items',
        );

        // type mixing is not allowed
        $this->assertThrowsWithMessage(
            ItemTypeMismatchException::class,
            'Mixing item types is not allowed',
            function () use ($list) {
                $list->add(new IntegerItem(1));
            }
        );
    }

    public function testAddItemAscendingInts()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createAscending();
        $list->add(new IntegerItem(2));
        Debug::debug($list->toArray());

        // prepend to the beginning
        $list->addIntValue(13);
        Debug::debug($list->toArray());

        // append to the end
        $list->add(new IntegerItem(1));
        Debug::debug($list->toArray());

        // insert in the middle
        $list->add(new IntegerItem(3));
        Debug::debug($list->toArray());

        // item gets pushed to the end of the list
        $this->assertEquals(
            [1, 2, 3, 13],
            $list->toArray(),
            'List should contain expected items',
        );

        // type mixing is not allowed
        $this->assertThrowsWithMessage(
            ItemTypeMismatchException::class,
            'Mixing item types is not allowed',
            function () use ($list) {
                $list->add(new StringItem('one'));
            }
        );
    }
}
