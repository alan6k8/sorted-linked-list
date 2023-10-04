<?php

declare(strict_types=1);

namespace Tests\Unit;

use Alan6k8\SortedLinkedList\Exception\ItemTypeMismatchException;
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
    public function testPushItem()
    {
        $list = new SortedLinkedList();
        $list->pushItem(new StringItem('Bladnoch'));

        $list->pushItem(new StringItem('Machrie Moor'));
        Debug::debug($list);

        // item gets pushed to the end of the list
        $this->assertEquals(
            ['Bladnoch', 'Machrie Moor'],
            $list->toArray(),
            'List should contain expected items',
        );

        // type mixing is not allowed
        $this->assertThrowsWithMessage(
            ItemTypeMismatchException::class,
            'Mixing item types is not allowed',
            function () use ($list) {
                $list->pushItem(new IntegerItem(1));
            }
        );
    }

    public function testUnshiftItem()
    {
        $list = new SortedLinkedList();
        $list->unshiftItem(new IntegerItem(10));

        $list->unshiftItem(new IntegerItem(20));
        Debug::debug($list);

        // item gets pushed to the end of the list
        $this->assertEquals(
            [20, 10],
            $list->toArray(),
            'List should contain expected items',
        );

        // type mixing is not allowed
        $this->assertThrowsWithMessage(
            ItemTypeMismatchException::class,
            'Mixing item types is not allowed',
            function () use ($list) {
                $list->unshiftItem(new StringItem('Lowland'));
            }
        );
    }
}
