<?php
include "Sql.php";

$dbh = new Conn('pgsql:dbname=db;host=192.168.2.4', 'gonzalo', 'password');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = new Sql($dbh);

$dbh->transactional(function() use ($sql) {
    $sql->insert('users', array('uid' => 7, 'name' => uniqid('name_'), 'surname' => uniqid('surname_'), ));
    $data = $sql->select('users', array('uid' => 7));
    print_r($data);
    $sql->update('users', array('name' => 'gonzalo'), array('uid' => 7));

    $data = $sql->select('users', array('uid' => 7));
    print_r($data);

    $sql->delete('users', array('uid' => 7));

    $data = $sql->select('users', array('uid' => 7));
    print_r($data);

});
