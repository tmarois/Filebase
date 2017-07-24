# Flatfile
Flat File Database

```

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
