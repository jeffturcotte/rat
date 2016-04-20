<?php
include __DIR__ . '/vendor/autoload.php';

use Rat\Manager;
use Rat\Entity;
use Rat\Storage;
use Rat\Identifier;

$pdo = new PDO('pgsql:host=localhost;dbname=jeff');

class User extends Entity {

}

$identifier = new Identifier();
$storage = new Storage($identifier, $pdo);
$manager = new Manager($storage);

// $user = $manager->get('User', '25e134844ccd8d72b941749da9f6fe62');

$test = new User();
$test->firstName = 'jim';
$test->lastName = 'bob';
$manager->save($test);
