# Filebase
A Very Simple Flat File Database Storage.


## Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require timothymarois/filebase` or add to your main `composer.json` file.

## Usage

```php
// configuration to your database
$config = \Filebase\Database::config([
    'dir' => 'path/to/database/dir'
]);

$my_database = new \Filebase\Database($config);

// load up a single item "4325663.json"
$item = $my_database->get('4325663');

// Set Variables
$item->first_name = 'John';
$item->last_name  = 'Smith';

// This will either save current changes to the object
// Or it will create a new object using the id "4325663"
// Saves to "4325663.json"
$item->save();
```


### Config Options

The config is *required* when defining your database. The options are *optional* as they have their own defaults.

```php
$config = \Filebase\Database::config([
    'dir'      => 'path/to/database/dir',
    'format'   => \Filebase\Format\Json::class
]);
```

|Name				|Type		|Default Value	    |Description												|
|---				|---		|---			         	|---														|
|`dir`				|string		|current directory          |The directory where the database files are stored. 	    |
|`format`			|object		|`\Filebase\Format\Json`   |The format class used to encode/decode data				|


### Change Format Class

You can write your own or change the existing format class in the config. The methods in the class must be `static` and the class must implement `\Filebase\Format\FormatInterface`

Current Default `JSON` Format Class:
```php
\Filebase\Format\Json::class
```


## Create / Update Documents

As listed in the above example, its **very simple**. Use `$item->save()`, the `save()` method will either **Create** or **Update** an existing document by default. It will log all changes with `createdAt` and `updatedAt`. If you want to replace *all* data within a single document pass the new data in the `save($data)` method, otherwise don't pass any data to allow it to save the current instance.

```php

// this will save the current document, and adding or replacing "title" variable
// but will leave existing variables unchanged.
$item->title = 'My Document';
$item->save()

// this will replace all data within the document
$item->save([
    'title' => 'My Document'
])

```

You can change the date output format by sending in a php date format within the parameter of  `createdAt($date_format)` and `updatedAt($date_format)`.

```php
$created_at = $item->createdAt();

// by default Y-m-d H:i:s
echo $created_at;


$updated_at = $item->updatedAt();

// by default Y-m-d H:i:s
echo $updated_at;
```


## API (Methods)

```php
// sets the configuration
$db::config()

// gets a single item by ID (loads up in the instance)
$db->get()

// returns all the entries within the database instance
$db->findAll()


// saves the current item in instance
$item->save()

// deletes the current item in instance
$item->delete()

// returns the items as an array instead of object
$item->toArray()
```


## Why Filebase?

I originally built Filebase because I needed more flexibility, control over the database files, how they are stored, query filtration and to design with very intuitive API methods.

Inspired by [Flywheel](https://github.com/jamesmoss/flywheel) and [Flinetone](https://github.com/fire015/flintstone).

## Contributions

Accepting contributions and feedback. Send in any issues and pull requests.


## TODO

- Data Validation (new config option)
- Indexing (adding indexed "tag like")
- Querying (searching for fields, and pulling in multiple doc results)
