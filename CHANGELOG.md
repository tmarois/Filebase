Change Log
==========

### 02/24/2019 - 1.0.24
* Merged [Pull Request](https://github.com/filebase/Filebase/pull/50) Fixed [bug](https://github.com/filebase/Filebase/issues/41) returning unexpected results.

### 02/24/2019 - 1.0.23
* Merged [Pull Request](https://github.com/filebase/Filebase/pull/49) Added support for order by multiple columns
* Merged [Pull Request](https://github.com/filebase/Filebase/pull/46) Added ability to query document ids (internal id)
* Added ability to use query `delete()` on all items that match the query (making the custom filter optional)

### 02/23/2019 - 1.0.22
* Merged [Pull Request](https://github.com/filebase/Filebase/pull/47) for deleting items with a custom filter. (this adds the `delete()` method on queries.)
* Merged [Pull Request](https://github.com/filebase/Filebase/pull/48) for calling to the Query methods directly on the database class.
* Merged [Pull Request](https://github.com/filebase/Filebase/pull/45) for sorting by update/created at times (ability to fetch `__created_at` and `__updated_at`)

### 12/26/2018 - 1.0.21
* Merged [Pull Request](https://github.com/filebase/Filebase/pull/30) for YAML format.

### 08/16/2018 - 1.0.20
* Fixed #23 – Caching is cleared when deleting/saving documents to prevent cache from being out of sync with document data.

### 08/13/2018 - 1.0.19
* Added #21 for checking database record exists, added `has()` method on database.

### 07/14/2018 - 1.0.18
* Fixed #17 for PHP 5.6
* Replaced the spaceship operators with a php 5.6 alternative.
* Removed the php 7 type hinting.
* Added php >= 5.6 as the new requirement for install.

### 07/06/2018 - 1.0.17
* Fixed #19 the `AND` query logic. (previously the `where()` query would only used the last one in the chain).

### 05/28/2018 - 1.0.16
* Fixed the scope resolution operator `::` for php 5.6 which throws the `T_PAAMAYIM_NEKUDOTAYIM` exception error.

### 03/13/2018 - 1.0.15
* Quick patch on composer.json file for dev dependency with satooshi/php-coveralls issues on dev-master. (this would have only affected new users from trying to install via composer.)

### 02/09/2018 - 1.0.14
* Added #11 a new configuration variable `read_only`. By default `false`, when set to `true` no modifications can be made to the database and if you attempt to make a `save()`, `delete()`, `truncate()` or `flush()` an exception will be thrown as those methods are not allowed to be used in read-only mode.
* The database will not attempt to create a new directory if one does not exist during read-only mode, this can become an issue if you don't have permission to do so, read-only tries to solve that.
* When set to `false` the database functions as normal.

### 12/14/2017 - 1.0.13
* Added #10 a new configuration variable `safe_filename`. By default `true`, suppresses any file name errors and converts the file name to a valid name, if set to `false`, an exception will be thrown upon a invalid name. All users who update will notice no errors will appear upon a invalid name. Set `safe_filename` to `false` if you prefer the exception to be thrown.

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
