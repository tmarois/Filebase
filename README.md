# Flatfile
Flat File Database


## Usage

```php

// configuration to your database
$config = \Flatfile\Database::config([
    'database' => 'path/to/database',
    'format'   => \Flatfile\Format\Json::class
]);


$my_database = new \Flatfile\Database($config);

// load up a single item
$item = $my_database->get('4325663');

// Set Variables
$item->first_name = 'John;
$item->last_name  = 'Smith;

// This will either save current changes to the object
// Or it will create a new object using the id "4325663"
$item->save();


```

## API (Methods)

```php

// gets a single item by ID (loads up in the instance)
$db->get()

// saves the current item in instance
$db->save()

// deletes the current item in instance
$db->delete()

// copies current document, and returns a new instance
$db->copy()

// returns the items as an array instead of object
$db->toArray()

// returns all the entries within the database instance
$db->findAll()

// sets the configuration
$db::config()

```
