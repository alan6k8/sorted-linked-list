<?php

declare(strict_types=1);

namespace Tests\Unit;

use Alan6k8\SortedLinkedList\Exception\ItemTypeMismatchException;
use Alan6k8\SortedLinkedList\Factory\SortedLinkedListFactory;
use Alan6k8\SortedLinkedList\Model\Item\IntegerItem;
use Alan6k8\SortedLinkedList\Model\Item\StringItem;
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

    public function testAddItemUniqueOnlyDescending()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createDescending(uniqueValuesOnly: true);
        $list->add(new IntegerItem(2));
        Debug::debug($list->toArray());

        $list->addIntValue(2);
        Debug::debug($list->toArray());

        $list->add(new IntegerItem(10));
        Debug::debug($list->toArray());

        $list->add(new IntegerItem(2));
        Debug::debug($list);

        $list->add(new IntegerItem(10));
        Debug::debug($list->toArray());

        $this->assertEquals(
            [10, 2],
            $list->toArray(),
            'List should contain expected unique items',
        );
    }

    public function testAddItemUniqueOnlyAscending()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createAscending(uniqueValuesOnly: true);
        $list->add(new IntegerItem(2));
        Debug::debug($list->toArray());

        $list->addIntValue(2);
        Debug::debug($list->toArray());

        $list->add(new IntegerItem(10));
        Debug::debug($list->toArray());

        $list->add(new IntegerItem(2));
        Debug::debug($list->toArray());

        $list->add(new IntegerItem(10));
        Debug::debug($list->toArray());

        $this->assertEquals(
            [2, 10],
            $list->toArray(),
            'List should contain expected unique items',
        );
    }

    public function testRemove()
    {
        $itemOne = new IntegerItem(2);
        $itemTwo = new IntegerItem(12);
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createAscending();
        $list->add($itemOne);
        $list->addIntValue(5);
        $list->add($itemTwo);
        $list->addIntValue(18);
        Debug::debug($list->toArray());

        $list->remove($itemOne);
        $this->assertFalse($list->hasItem($itemOne));

        $list->remove($itemTwo);
        $this->assertFalse($list->hasItem($itemTwo));

        Debug::debug($list->toArray());
        $this->assertEquals([5, 18], $list->toArray(), 'List should contain expected items');
    }

    public function testShift()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createAscending();
        $list->add(new IntegerItem(20));
        $list->add(new IntegerItem(10));
        Debug::debug($list->toArray());

        $shifted = $list->shift();
        Debug::debug($list->toArray());
        Debug::debug($shifted);

        $this->assertEquals(10, $shifted->getValue(), 'shifted item should have value of 10');
        $this->assertFalse($shifted->hasNext(), 'shifted item should point to null as next item');

        $shifted = $list->shift();
        Debug::debug($list->toArray());
        Debug::debug($shifted);

        $this->assertEquals(20, $shifted->getValue(), 'shifted item should have value of 10');
        $this->assertFalse($shifted->hasNext(), 'shifted item should point to null as next item');
        $this->assertTrue($list->isEmpty(), 'List should be empty');
    }

    public function testPop()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createDescending();
        $list->add(new IntegerItem(20));
        $list->add(new IntegerItem(10));
        Debug::debug($list->toArray());

        $shifted = $list->pop();
        Debug::debug($list->toArray());
        Debug::debug($shifted);

        $this->assertEquals(10, $shifted->getValue(), 'shifted item should have value of 10');
        $this->assertFalse($shifted->hasNext(), 'popped item should point to null as next item');

        $shifted = $list->pop();
        Debug::debug($list->toArray());
        Debug::debug($shifted);

        $this->assertEquals(20, $shifted->getValue(), 'shifted item should have value of 10');
        $this->assertFalse($shifted->hasNext(), 'popped item should point to null as next item');
        $this->assertTrue($list->isEmpty(), 'List should be empty');
    }

    public function testIterate()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createAscending();
        $list->add(new IntegerItem(20));
        $list->add(new IntegerItem(10));
        $list->add(new IntegerItem(30));
        $list->add(new IntegerItem(40));
        Debug::debug($list->toArray());

        $i = 1;
        foreach ($list->iterate() as $value) {
            $this->assertEquals($i * 10, $value->getValue());
            ++$i;
        }
    }

    public function testHasValue()
    {
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createAscending();
        $list->add(new IntegerItem(2));
        $list->add(new IntegerItem(4));
        $list->add(new IntegerItem(6));
        Debug::debug($list->toArray());

        $this->assertTrue($list->hasValue(4), 'List should contain 4');

        $list = $listFactory->createAscending();
        $list->add(new StringItem('Venus'));
        $list->add(new StringItem('Earth'));
        $list->add(new StringItem('Mars'));
        Debug::debug($list->toArray());

        $this->assertTrue($list->hasValue('Earth'), 'List should contain Earth');
    }

    public function testHasItem()
    {
        $expectedItem = new IntegerItem(2);
        $listFactory = new SortedLinkedListFactory();
        $list = $listFactory->createAscending();
        $list->add($expectedItem);
        $list->add(new IntegerItem(4));
        $list->add(new IntegerItem(6));
        Debug::debug($list->toArray());

        $this->assertTrue($list->hasItem($expectedItem), 'List should contain expected item');

        $expectedItem = new StringItem('Mars');
        $list = $listFactory->createAscending();
        $list->add(new StringItem('Venus'));
        $list->add(new StringItem('Earth'));
        $list->add($expectedItem);
        Debug::debug($list->toArray());

        $this->assertTrue($list->hasItem($expectedItem), 'List should contain expected item');
    }
}
