# Filebase

[![Build Status](https://travis-ci.org/filebase/Filebase.svg?branch=1.0)](https://travis-ci.org/filebase/Filebase) [![Coverage Status](https://coveralls.io/repos/github/filebase/Filebase/badge.svg?branch=1.0)](https://coveralls.io/github/filebase/Filebase?branch=1.0) [![Slack](http://timothymarois.com/a/slack-02.svg)](https://join.slack.com/t/basephp/shared_invite/enQtNDI0MzQyMDE0MDAwLWU3Nzg0Yjk4MjM0OWVmZDZjMjEyYWE2YjA1ODFhNjI2MzI3MjAyOTIyOTRkMmVlNWNhZWYzMTIwZDJlOWQ2ZTA)

A Simple but Powerful Flat File Database Storage. No need for MySQL or an expensive SQL server, in fact, you just need your current site or application setup. All database entries are stored in files ([formatted](https://github.com/filebase/Filebase#2-formatting) the way you like).

You can even modify the raw data within the files themselves without ever needing to use the API. And even better you can put all your files in **version control** and pass them to your team without having out-of-sync SQL databases.

Doesn't that sound awesome?

With Filebase, you are in complete control. Design your data structure the way you want. Use arrays and objects like you know how in PHP. Update and share your data with others and teams using version control. Just remember, upgrading your web/apache server is a lot less than your database server.

Works with **PHP 5.6** and **PHP 7+**

### Features

Filebase is simple by design, but has enough features for the more advanced.

* Key/Value and Array-based Data Storing
* [Querying data](https://github.com/filebase/Filebase#8-queries)
* [Custom filters](https://github.com/filebase/Filebase#7-custom-filters)
* [Caching](https://github.com/filebase/Filebase#9-caching) (queries)
* [Database Backups](https://github.com/filebase/Filebase#10-database-backups)
* [Formatting](https://github.com/filebase/Filebase#2-formatting) (encode/decode)
* [Validation](https://github.com/filebase/Filebase#6-validation-optional) (on save)
* CRUD (method APIs)
* File locking (on save)
* Intuitive Method Naming

## Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require filebase/filebase:^1.0`

If you do not want to use composer, download the files, and include it within your application, it does not have any dependencies, you will just need to keep it updated with any future releases.

## Usage

```php
// setting the access and configration to your database
$database = new \Filebase\Database([
    'dir' => 'path/to/database/dir'
]);

// in this example, you would search an exact user name
// it would technically be stored as user_name.json in the directories
// if user_name.json doesn't exists get will return new empty Document
$item = $database->get('kingslayer');

// display property values
echo $item->first_name;
echo $item->last_name;
echo $item->email;

// change existing or add new properties
$item->email = 'example@example.com';
$item->tags  = ['php','developer','html5'];

// need to save? thats easy!
$item->save();


// check if a record exists and do something if it does or does not
if ($database->has('kingslayer'))
{
    // do some action
}

// Need to find all the users that have a tag for "php" ?
$users = $db->where('tags','IN','php')->results();

// Need to search for all the users who use @yahoo.com email addresses?
$users = $db->where('email','LIKE','@yahoo.com')->results();

```


## (1) Config Options

The config is *required* when defining your database. The options are *optional* since they have defaults.

Usage Example (all options)

```php
$db = new \Filebase\Database([
    'dir'            => 'path/to/database/dir',
    'backupLocation' => 'path/to/database/backup/dir',
    'format'         => \Filebase\Format\Json::class,
    'cache'          => true,
    'cache_expires'  => 1800,
    'pretty'         => true,
    'safe_filename'  => true,
    'read_only'      => false,
    'validate' => [
        'name'   => [
            'valid.type' => 'string',
            'valid.required' => true
        ]
    ]
]);
```

|Name				|Type		|Default Value	    |Description												|
|---				|---		|---			         	|---														|
|`dir`				|string		|current directory          |The directory where the database files are stored. 	    |
|`backupLocation`   |string		|current directory (`/backups`)         |The directory where the backup zip files will be stored. 	    |
|`format`			|object		|`\Filebase\Format\Json`   |The format class used to encode/decode data				|
|`validate`			|array		|   |Check [Validation Rules](https://github.com/filebase/Filebase#6-validation-optional) for more details |
|`cache`			|bool		|true   |Stores [query](https://github.com/filebase/Filebase#8-queries) results into cache for faster loading.				|
|`cache_expire`		|int		|1800   |How long caching will last (in seconds)	|
|`pretty`	    	|bool		|true   |Store the data for human readability? Pretty Print	|
|`safe_filename`	|bool		|true   |Automatically converts the file name to a valid name (added: 1.0.13)   |
|`read_only`        |bool		|false  |Prevents the database from creating/modifying files or directories (added: 1.0.14)	|


## (2) Formatting

Format Class is what defines the encoding and decoding of data within your database files.

You can write your own or change the existing format class in the config. The methods in the class must be `static` and the class must implement `\Filebase\Format\FormatInterface`

The Default Format Class: `JSON`
```php
\Filebase\Format\Json::class
```

Additional Format Classes: `Yaml`
```php
\Filebase\Format\Yaml::class
```

## (3) GET (and methods)

After you've loaded up your database config, then you can use the `get()` method to retrieve a single document of data.

If document does not exist, it will create a empty object for you to store data into. You can then call the `save()` method and it will create the document (or update an existing one).

```php
// my user id
$userId = '92832711';

// get the user information by id
$item = $db->get($userId);

```

`get()` returns `\Filebase\Document` object and has its own methods which you can call.

|Method|Details|
|---|---|
|`save()`                         | Saves document in current state |
|`delete()`                       | Deletes current document (can not be undone) |
|`toArray()`                      | Array of items in document |
|`getId()`                        | Document Id |
|`createdAt()`                    | Document was created (default Y-m-d H:i:s) |
|`updatedAt()`                    | Document was updated (default Y-m-d H:i:s) |
|`field()`                        | You can also use `.` dot delimiter to find values from nested arrays |
|`isCache()`                      | (true/false) if the current document is loaded from cache |
|`filter()`                       | Refer to the [Custom Filters](https://github.com/filebase/Filebase#7-custom-filters) |

Example:

```php
// get the timestamp when the user was created
echo $db->get($userId)->createdAt();

// grabbing a specific field "tags" within the user
// in this case, tags might come back as an array ["php","html","javascript"]
$user_tags = $db->get($userId)->field('tags');

// or if "tags" is nested in the user data, such as about[tags]
$user_tags = $db->get($userId)->field('about.tags');

// and of course you can do this as well for getting "tags"
$user = $db->get($userId);
$user_tags = $user->tags;
$user_tags = $user->about['tags'];
```


## (4) Create | Update | Delete

As listed in the above example, its **very simple**. Use `$item->save()`, the `save()` method will either **Create** or **Update** an existing document by default. It will log all changes with `createdAt` and `updatedAt`. If you want to replace *all* data within a single document pass the new data in the `save($data)` method, otherwise don't pass any data to allow it to save the current instance.

```php

// SAVE or CREATE
// this will save the current data and any changed variables
// but it will leave existing variables that you did not modify unchanged.
// This will also create a document if none exist.
$item->title = 'My Document';
$item->save()

// This will replace all data within the document
// Allows you to reset the document and put in fresh data
// Ignoring any above changes or changes to variables, since
// This sets its own within the save method.
$item->save([
    'title' => 'My Document'
]);

// DELETE
// This will delete the current document
// This action can not be undone.
$item->delete();

```


## (5) Database Methods

```php
$db = new \Filebase\Database($config);
```

Here is a list of methods you can use on the database class.

|Method|Details|
|---|---|
|`version()`                      | Current version of your Filebase library |
|`get($id)`                       | Refer to [get()](https://github.com/filebase/Filebase#3-get-and-methods) |
|`has($id)`                       | Check if a record exist returning true/false |
|`findAll()`                      | Returns all documents in database |
|`count()`                        | Number of documents in database |
|`flush(true)`                    | Deletes all documents. |
|`flushCache()`                   | Clears all the cache |
|`truncate()`                     | Deletes all documents. Alias of `flush(true)` |
|`query()`                        | Refer to the [Queries](https://github.com/filebase/Filebase#8-queries) |
|`backup()`                       | Refer to the [Backups](https://github.com/filebase/Filebase#10-database-backups) |

Examples

```php
$users = new \Filebase\Database([
    'dir' => '/storage/users',
]);

// displays number of users in the database
echo $users->count();


// Find All Users and display their email addresses

$results = $users->findAll();
foreach($results as $user)
{
    echo $user->email;

    // you can also run GET methods on each user (document found)
    // Displays when the user was created.
    echo $user->createdAt();
}


// deletes all users in the database
// this action CAN NOT be undone (be warned)
$users->flush(true);

```


## (6) Validation *(optional)*

When invoking `save()` method, the document will be checked for validation rules (if set).
These rules MUST pass in order for the document to save.

```php
$db = new \Filebase\Database([
    'dir' => '/path/to/database/dir',
    'validate' => [
        'name'   => [
            'valid.type' => 'string',
            'valid.required' => true
        ],
        'description' => [
            'valid.type' => 'string',
            'valid.required' => false
        ],
        'emails' => [
            'valid.type'     => 'array',
            'valid.required' => true
        ],
        'config' => [
            'settings' => [
                'valid.type'     => 'array',
                'valid.required' => true
            ]
        ]
    ]
]);
```

In the above example `name`, `description`, `emails` and `config` array keys would be replaced with your own that match your data. Notice that `config` has a nested array `settings`, yes you can nest validations.

**Validation rules:**

|Name				|Allowed Values		|Description		                |
|---				|---		                                            |---		|
|`valid.type`				|`string`, `str`, `integer`, `int`, `array`		|Checks if the property is the current type		|
|`valid.required`			|`true`, `false`		                                |Checks if the property is on the document		|


## (7) Custom Filters

*NOTE Custom filters only run on a single document*

Item filters allow you to customize the results, and do simple querying within the same document. These filters are great if you have an array of items within one document. Let's say you store "users" as an array in `users.json`, then you could create a filter to show you all the users that have a specific tag, or field matching a specific value.

This example will output all the emails of users who are blocked.

```php
// Use [data] for all items within the document
// But be sure that each array item uses the same format (otherwise except isset errors)

$users = $db->get('users')->filter('data','blocked',function($item, $status) {
    return (($item['status']==$status) ? $item['email'] : false);
});

// Nested Arrays?
// This uses NESTED properties. If the users array was stored as an array inside [list]
// You can also use `.` dot delimiter to get arrays from nested arrays

$users = $db->get('users')->filter('list.users','blocked',function($item, $status) {
    return (($item['status']==$status) ? $item['email'] : false);
});
```

## (8) Queries

Queries allow you to search **multiple documents** and return only the ones that match your criteria.

If caching is enabled, queries will use `findAll()` and then cache results for the next run.

> Note: You no longer need to call `query()`, you can now call query methods directly on the database class.

```php
// Simple (equal to) Query
// return all the users that are blocked.
$users = $db->where(['status' => 'blocked'])->results();

// Stackable WHERE clauses
// return all the users who are blocked,
// AND have "php" within the tag array
$users = $db->where('status','=','blocked')
            ->andWhere('tag','IN','php')
            ->results();

// You can also use `.` dot delimiter to use on nested keys
$users = $db->where('status.language.english','=','blocked')->results();

// Limit Example: Same query as above, except we only want to limit the results to 10
$users = $db->where('status.language.english','=','blocked')->limit(10)->results();



// Query LIKE Example: how about find all users that have a gmail account?
$usersWithGmail = $db->where('email','LIKE','@gmail.com')->results();

// OrderBy Example: From the above query, what if you want to order the results by nested array
$usersWithGmail = $db->where('email','LIKE','@gmail.com')
                     ->orderBy('profile.name', 'ASC')
                     ->results();

// or just order the results by email address
$usersWithGmail = $db->where('email','LIKE','@gmail.com')
                     ->orderBy('email', 'ASC')
                     ->results();

// OrderBy can be applied multiple times to perform a multi-sort
$usersWithGmail = $db->query()
                    ->where('email','LIKE','@gmail.com')
                    ->orderBy('last_name', 'ASC')
                    ->orderBy('email', 'ASC')
                    ->results();

// this will return the first user in the list based on ascending order of user name.
$user = $db->orderBy('name','ASC')->first();
// print out the user name
echo $user['name'];

// You can also order multiple columns as such (stacking)
$orderMultiples = $db->orderBy('field1','ASC')
                     ->orderBy('field2','DESC')
                     ->results();

// What about regex search? Finds emails within a field
$users = $db->where('email','REGEX','/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i')->results();

// Find all users that have gmail addresses and only returning their name and age fields (excluding the rest)
$users = $db->select('name,age')->where('email','LIKE','@gmail.com')->results();

// Instead of returning users, how about just count how many users are found.
$totalUsers = $db->where('email','LIKE','@gmail.com')->count();


// You can delete all documents that match the query (BULK DELETE)
$db->where('name','LIKE','john')->delete();

// Delete all items that match query and match custom filter
$db->where('name','LIKE','john')->delete(function($item){
    return ($item->name == 'John' && $item->email == 'some@mail.com');
});


// GLOBAL VARIABLES

// ability to sort the results by created at or updated at times
$documents = $db->orderBy('__created_at', 'DESC')->results();
$documents = $db->orderBy('__updated_at', 'DESC')->results();

// search for items that match the (internal) id
$documents = $db->where('__id', 'IN', ['id1', 'id2'])->results();

```

To run the query use `results()` or if you only want to return the first item use `first()`

### Query Methods:

*These methods are optional and they are stackable*

|Method                 |Arguments                              |Details
|---                    |---                                    |---|
|`select()`             | `array` or `string` (comma separated) | Select only the fields you wish to return (for each document), usage: `field1,field2` |
|`where()`              | `mixed`                               | `array` for simple "equal to" OR `where($field, $operator, $value)` |
|`andWhere()`           | `mixed`                               | see `where()`, uses the logical `AND` |
|`orWhere()`            | `mixed`                               | see `where()`, this uses the logical `OR` |
|`limit()`              | `int` limit, `int` offset             | How many documents to return, and offset |
|`orderBy()`            | `field` , `sort order`                | Order documents by a specific field and order by `ASC` or `DESC` |
|`delete()`             | `Closure`                             | Ability to Bulk-delete all items that match |


The below **methods execute the query** and return results *(do not try to use them together)*

|Method                 |Details|
|---                    |---|
|`count()`              | Counts and returns the number of documents in results. |
|`first()`              | Returns only the first document in results. |
|`last()`               | Returns only the last document in results. |
|`results()`            | This will return all the documents found and their data as an array. Passing the argument of `false` will be the same as `resultDocuments()` (returning the full document objects) |
|`resultDocuments()`    | This will return all the documents found and their data as document objects, or you can do `results(false)` which is the alias. |

### Comparison Operators:

|Name				|Details|
|---				|---|
|`=` or `==`        |Equality|
|`===`              |Strict Equality|
|`!=`               |Not Equals|
|`NOT`              |Not Equals (same as `!=`)|
|`!==`              |Strict Not Equals|
|`>`                |Greater than|
|`>=`               |Greater than or equal|
|`<`                |Less than|
|`<=`               |Less than or equal|
|`IN`               |Checks if the value is within a array|
|`LIKE`             |case-insensitive regex expression search|
|`NOT LIKE`         |case-insensitive regex expression search (opposite)|
|`REGEX`            |Regex search|


## (9) Caching
If caching is enabled, it will automatically store your results from queries into sub-directories within your database directory.

Cached queries will only be used if a specific saved cache is less than the expire time, otherwise it will use live data and automatically replace the existing cache for next time use.


## (10) Database Backups
By default you can backup your database using `$db->backup()->create()`, this will create a `.zip` file of your entire database based on your `dir` path.

### Methods:
These methods can be used when invoking `backup()` on your `Database`.

- `create()` Creates a backup of your database (in your backup location `.zip`)
- `clean()` Purges all existing backups (`.zip` files in your backup location)
- `find()` Returns an `array` of all existing backups (array key by `time()` when backup was created)
- `rollback()` Restore an existing backup (latest available), replaces existing database `dir`

**Example:**

```php
// invoke your database
$database = new \Filebase\Database([
    'dir' => '/storage/users',
    'backupLocation' => '/storage/backup',
]);

// create a new backup of your database
// will look something like /storage/backup/1504631092.zip
$database->backup()->create();

// delete all existing backups
$database->backup()->clean();

// get a list of all existing backups (organized from new to old)
$backups = $database->backup()->find();

// restore an existing backup (latest backup available)
$database->backup()->rollback();

```


## Why Filebase?

Filebase was built for the flexibility to help manage simple data storage without the hassle of a heavy database engine. The concept of Filebase is to provide very intuitive API methods, and make it easy for the developer to maintain and manage (even on a large scale).

Inspired by [Flywheel](https://github.com/jamesmoss/flywheel) and [Flinetone](https://github.com/fire015/flintstone).


## How Versions Work

Versions are as follows: Major.Minor.Patch

* Major: Rewrites with completely new code-base.
* Minor: New Features/Changes that breaks compatibility.
* Patch: New Features/Fixes that does not break compatibility.

Filebase will work-hard to be **backwards-compatible** when possible.


## Sites and Users of Filebase

* [Grayscale Inc](https://grayscale.com)
* [VIP Auto](http://vipautoli.com)
* [Ideal Internet](http://idealinternet.com)
* [OnlineFun](http://onlinefun.com)
* [PuzzlePlay](http://puzzleplay.com)
* [Square Media LLC](http://squaremedia.com)
* [My Map Directions](https://mymapdirections.com)
* [Discount Savings](https://discount-savings.com)
* [Vivint - Smart Homes](http://smarthomesecurityplans.com/)

*If you are using Filebase – send in a pull request and we will add your project here.*


## Contributions

Anyone can contribute to Filebase. Please do so by posting issues when you've found something that is unexpected or sending a pull request for improvements.


## License

Filebase is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
