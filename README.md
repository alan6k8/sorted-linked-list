# Sorted Linked List

** Reusable sorted linked list **

[![Unit Tests](https://github.com/alan6k8/sorted-linked-list/actions/workflows/php.yml/badge.svg)](https://github.com/alan6k8/sorted-linked-list/actions/workflows/php.yml)

Simple library that provides list (or a chain) of nodes which are automatically sorted by value they hold.

List can be invoked either directly via new keyword or via _SortedLinkedListFactory_. One may find factory approach to be more suitable for DI and mocking.
```php
$listFactory = new SortedLinkedListFactory();
// invokes list sorted in ascending order
$list = $listFactory->createAscending();
// adds 2
$list->add(new IntegerItem(2));
```

## Usage

List supports following params:

- *sortOrder* - tells sort order of the list (ASC or DESC) and is represented by enum class _LinkedListSortOrder_.
```php
new SortedLinkedList(LinkedListSortOrder::ASC, $uniqueValuesOnly);
```

- *uniqueValuesOnly* - a flag that enforces enlisting of an item (or values respectively as value gets internally transformed to item) that holds unique value (one that no other enlisted item holds). Duplicates are ignored.
```php
new SortedLinkedList(LinkedListSortOrder::ASC, true);
```

## Installation

Library is installed via [Composer](https://getcomposer.org/).


```
composer require alan6k8/sorted-linked-list
```
