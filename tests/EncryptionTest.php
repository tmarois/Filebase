<?php  

require_once __DIR__.'/../vendor/autoload.php';

$db = new \Filebase\Database([
    'dir' => __DIR__.'/databases',
    'encryption' => array('key_storage_path' => __DIR__.'/encrypter', 'key_name' => 'test')
]);
$db->flush(true);
$user = $db->get(uniqid());
$user->name  = 'John';
$user->email = 'john@example.com';
$user->save();
$db->where('name','=','John')->andWhere('email','==','john@example.com')->select('email')->results();
$result_from_cache = $db->where('name','=','John')->andWhere('email','==','john@example.com')->select('email')->results();
print_r($result_from_cache);