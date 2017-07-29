# Filebase

[![Build Status](https://travis-ci.org/timothymarois/Filebase.svg?branch=master)](https://travis-ci.org/timothymarois/Filebase) [![Coverage Status](https://coveralls.io/repos/github/timothymarois/Filebase/badge.svg?branch=master)](https://coveralls.io/github/timothymarois/Filebase?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/timothymarois/Filebase/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/timothymarois/Filebase/?branch=master)

A Simple but Powerful **Flat File Database** Storage. No need for MySQL or a expensive SQL server, in fact you just need your current site or application setup. All database entries are stored in files ([formatted](https://github.com/timothymarois/Filebase#2-formatting) the way you like).

You can even modify the raw data within the files themselves without ever needing to use the API. And even better you can put all your files in **version control** and pass them to your team without having out-of-sync SQL databases. Doesn't that sound awesome?

### Features

Filebase is simple by design, but also has enough features for even the more advanced.

* Key-value and Multidimensional Data Storing
* Querying data
* Custom filters
* Caching (queries)
* File locking (on save)
* Customizable formatting (encode/decode)
* Validation (on save)


## Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require timothymarois/filebase` or add to your main `composer.json` file.

## Usage

```php
// setting the access and configration to your database
$my_database = new \Filebase\Database([
    'dir' => 'path/to/database/dir'
]);

// in this example, you would replace user_name with the actual user name.
// It would technically be stored as user_name.json
$item = $my_database->get('user_name');

// display property values
echo $item->first_name;
echo $item->last_name;
echo $item->email;

// change existing or add new properties
$item->email = 'example@example.com';

// need to save? thats easy!
$item->save();

```


## (1) Config Options

The config is *required* when defining your database. The options are *optional* since they have defaults.

Usage Example (all options)

```php
$db = new \Filebase\Database([
    'dir'           => 'path/to/database/dir',
    'format'        => \Filebase\Format\Json::class,
    'cache'         => true,
    'cache_expires' => 1800,
    'pretty'        => true,
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
|`format`			|object		|`\Filebase\Format\Json`   |The format class used to encode/decode data				|
|`validate`			|array		|   |Check [Validation Rules](https://github.com/timothymarois/Filebase#6-validation-optional) for more details |
|`cache`			|bool		|false   |Stores [query](https://github.com/timothymarois/Filebase#8-queries) results into cache for faster loading.				|
|`cache_expire`		|int		|1800   |How long caching will last (in seconds)	|
|`pretty`	    	|bool		|true   |Store the data for human readability? Pretty Print	|


## (2) Formatting

Format Class is what defines the encoding and decoding of data within your database files.

You can write your own or change the existing format class in the config. The methods in the class must be `static` and the class must implement `\Filebase\Format\FormatInterface`

The Default Format Class: `JSON`
```php
\Filebase\Format\Json::class
```


## (3) GET (and methods)

After you've loaded up your database config, then you can use the `get()` method to retrieve a single document of data.

```php
// my user id
$user_id = '92832711';

// get the user information by id
$item = $db->get($user_id);
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
|`customFilter()`                 | Refer to the [Custom Filters](https://github.com/timothymarois/Filebase#7-custom-filters) |

Example:

```php
// get the timestamp when the user was created
echo $db->get($user_id)->createdAt();

// grabbing a specific field "tags" within the user
// in this case, tags might come back as an array ["php","html","javascript"]
$user_tags = $db->get($user_id)->field('tags');

// or if "tags" is nested in the user data, such as aboutme->tags
$user_tags = $db->get($user_id)->field('aboutme.tags');

// and of course you can do this as well for getting "tags"
$user = $db->get($user_id);
$user_tags = $user->tags;
$user_tags = $user->aboutme->tags;
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
|`get()`                          | Refer to [get()](https://github.com/timothymarois/Filebase#3-get-and-methods) |
|`findAll()`                      | Returns all Documents in database |
|`count()`                        | Number of documents in database |
|`flush(true)`                    | Deletes all documents |
|`flushCache()`                   | Clears all the cache |
|`query()`                        | Refer to the [Queries](https://github.com/timothymarois/Filebase#8-queries) |

Examples

```php
$users = new \Filebase\Database([
    'dir' => '/storage/users',
]);

// displays number of users in the database
echo $users->count();


// Find All Users and display their email addresses

$users->findAll();
foreach($users as $user)
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

Item filters allow you to customize the results, and do simple querying. These filters are great if you have an array of items within one document. Let's say you store "users" as an array in `users.json`, then you could create a filter to show you all the users that have a specific tag, or field matching a specific value.

This example will output all the emails of users who are blocked.

```php
// Use [data] for all items within the document
// But be sure that each array item uses the same format

$users = $db->get('users')->customFilter('data',function($item) {
    return (($item['status']=='blocked') ? $item['email'] : false);
});

// Nested Arrays?
// This uses NESTED properties. If the users array was stored as an array inside [list]
// You can also use `.` dot delimiter to get arrays from nested arrays

$users = $db->get('users')->customFilter('list.users',function($item) {
    return (($item['status']=='blocked') ? $item['email'] : false);
});
```

## (8) Queries

Queries allow you to search **multiple documents** and return only the ones that match your criteria.

If caching is enabled, queries will use `findAll()` and then cache results for the next run.

```php
// Simple (equal to) Query
// return all the users that are blocked.
$users = $userdb->query()
    ->where(['status' => 'blocked'])
    ->results();

// Stackable WHERE clauses
// return all the users who are blocked,
// AND have "php" within the tag array
$users = $userdb->query()
    ->where('status','=','blocked')
    ->where('tag','IN','php')
    ->results();

// You can also use `.` dot delimiter to use on nested keys
$users = $userdb->query()->where('status.language.english','=','blocked')->results();
```

To run the query use `results()`

### Methods:

- `where()` param `array` for simple "equal to" OR `where($field, $operator, $value)`
- `andWhere()` *optional* see `where()`, uses the logical `AND`
- `orWhere()` *optional* see `where()`, this uses the logical `OR`
- `results()` This will return all the document objects.

### Comparison Operators:

|Name				|Details|
|---				|---|
|`=` or `==`        |Equality|
|`===`              |Strict Equality|
|`!=`               |Not Equals|
|`!==`              |Strict Not Equals|
|`>`                |Greater than|
|`>=`               |Greater than or equal|
|`<`                |Less than|
|`<=`               |Less than or equal|
|`IN`               |Checks if the value is within a array|


## (9) Caching
If caching is enabled, it will automatically store your results from queries into sub-directories within your database directory.

Cached queries will only be used if a specific saved cache is less than the expire time, otherwise it will use live data and automatically replace the existing cache for next time use.


## Why Filebase?

I originally built Filebase because I needed more flexibility, control over the database files, how they are stored, query filtration and a design with very intuitive API methods.

Inspired by [Flywheel](https://github.com/jamesmoss/flywheel) and [Flinetone](https://github.com/fire015/flintstone).

## Contributions

Accepting contributions and feedback. Send in any issues and pull requests.

## TODO

- Indexing (adding indexed "tags" for all document searching)
- Indexing (single document filtering, applied with all `save()` actions from validation closure)
- Internal validations..security etc.
- Cache driver (to use on other services like memcached, redis etc)
