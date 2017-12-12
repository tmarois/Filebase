Change Log
==========

### 12/11/2017 - 1.0.12
* Added #8 - `select()` method on query class. Now allows you to specify which fields you want your documents to return. *Note: using `select` means your documents will not return document objects but only data arrays.* This will allow you to only include the fields you want to use for your current task. (Excluding the rest and reducing memory usage).
* Added `last()` method on query class to return the last item in the result array (opposite of `first()`)
* Added `count()` method on query class to return the number of documents found by the query.

### 12/11/2017 - 1.0.11
* Fixed query `sort` which allows for "natural order", issues before would assume "1" and "10" are equal in value, but this has been resolved with this update. Uses php `strnatcasecmp()`, This was fixed for `DESC` order in the previous update. This patch fixes the `ASC` sort order.

### 12/10/2017 - 1.0.10
* Fixed query `sort` which allows for "natural order", issues before would assume "1" and "10" are equal in value, but this has been resolved with this update. Uses php `strnatcasecmp()`
* Added argument `results( false )` - `false` on `results()` method that allows it to return the full document object or (by default = `true`) only the document data.
* Added argument `first( false )` - `false` on `first()` method that allows it to return the full document object or (by default = `true`) only the document data.
* Minor additions to the documentation.

### 09/09/2017 - 1.0.9
* Fixed `customFilter` on #5 issue with array keys not properly resetting.
* Improved speed of `filter()` since it was running the function closure function twice
* Added alias of `customFilter()` as `filter()` method.
* Added `version()` method on database class. `$db->version()` for the Filebase version number.

### 09/08/2017 - 1.0.8
* Updated `customFilter` method to allow passable parameters into closure function. (backwards compatibility allowing param and function arguments to be any order)

### 09/05/2017 - 1.0.7
* Added `rollback()` method on the backup class. Now the ability to restore an existing back up (latest one available)

### 09/05/2017 - 1.0.6
* Added new `backup` class and functionality to create database backups.
* Added `create()`, `clean()` and `find()` methods on backup class.

Accessible when invoked on your database `$db->backup->create()`, Rollbacks are on the to do list for the next update. This update includes the ability to create new backups and deletes or shows existing backups for your own records. *Restoring from a previous backup is on the todo list.*

### 08/06/2017 - 1.0.5
* Added new method database class `truncate()` as an alias of `flush(true)`
* Added `REGEX` as new query operator. Uses `preg_match($regex, $fieldValue)`

### 08/05/2017 - 1.0.4
* Added `first()` (if you want to only return the first array of the query result)
* Ability to use Queries without needing `where()`, can now use queries to find all and order results
* Fixed Predicate Exceptions for bad query arguments (now correctly parsing them)

### 08/05/2017 - 1.0.3
* Added `orderBy()` (sorting field and direction `ASC` and `DESC`)
* Added `limit()` Limit results returned, includes Limit and Offset options.

### 08/05/2017 - 1.0.2
* Added the `NOT LIKE` operator

### 08/04/2017 - 1.0.1
* Added the `LIKE` operator and few small tweaks

### 08/04/2017 - 1.0.0
* Initial Release
