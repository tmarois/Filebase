# Flatfile
Flat File Database


## Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require timothymarois/filebase` or add to your main `composer.json` file.


## Usage

```php
// configuration to your database
$config = \Filebase\Database::config([
    'database' => 'path/to/database',
    'format'   => \Filebase\Format\Json::class
]);

$my_database = new \Filebase\Database($config);

// load up a single item
$item = $my_database->get('4325663');

// Set Variables
$item->first_name = 'John';
$item->last_name  = 'Smith';

// This will either save current changes to the object
// Or it will create a new object using the id "4325663"
$item->save();
```


## Create / Update Documents

As listed in the above example, its **very simple**. Use `$item->save()`, the `save()` method will either **Create** or **Update** an existing document by default. It will log all changes with `createdAt` and `updatedAt`. If you want to replace *all* data within a single document pass the new data in the `save($data)` method, otherwise don't pass any data to allow it to save the current instance.

```php

// this will save the current document, and adding or replacing "title" variable
// but will leave existing variables unchanged.
$item->title = 'My Document';
$item->save()

// this will replace all data within the document
item->save([
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
