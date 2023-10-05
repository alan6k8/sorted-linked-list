# Sorted Linked List
Simple library that provides sorted linked list. List is basicaly a chain of nodes which are automaticaly sorted by value they hold.

List can be invoked either directly via new keyword or via Factory (Factory can be more suitable for DI and mocking).

_List supports following params:_

- sortOrder - tells whether ASC or DESC
- uniqueValuesOnly - a flag that enforces enlisting of item (or values respectively as value gets internaly transformed to item) that holds unique value (one that no other enlisted item holds). Duplicates are ignored.
