# Flatfile
Flat File Database

```

// gets a single item by ID (loads up in the instance)
$db->get()

// saves the current item
$db->save()

// delets the current item
$db->delete()

// copies current document, and returns a new instance
$db->copy()

// returns the items as an array instead of object
$db->toArray()

// returns all the entries within a database
$db->findAll()

// sets the configuration
$db::config()

```
