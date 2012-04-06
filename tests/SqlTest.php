<?php
include "../Sql.php";

class SqlTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dbh = new Conn('pgsql:dbname=db;host=192.168.2.4', 'gonzalo', 'password');
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh->forceRollback();
    }

    public function testTransactions()
    {

        $sql = new Sql($this->dbh);
        $that = $this;

        $this->dbh->transactional(function($dbh) use ($sql, $that) {
            $actual = $sql->insert('users', array('uid' => 7, 'name' => 'Gonzalo', 'surname' => 'Ayuso'));
            $that->assertTrue($actual);

            $actual = $sql->insert('users', array('uid' => 8, 'name' => 'Peter', 'surname' => 'Parker'));
            $that->assertTrue($actual);

            $data = $sql->select('users', array('uid' => 8));
            $that->assertEquals('Peter', $data[0]['name']);
            $that->assertEquals('Parker', $data[0]['surname']);

            $sql->update('users', array('name' => 'gonzalo'), array('uid' => 7));

            $data = $sql->select('users', array('uid' => 7));
            $that->assertEquals('gonzalo', $data[0]['name']);

            $data = $sql->delete('users', array('uid' => 7));

            $data = $sql->select('users', array('uid' => 7));
            $that->assertTrue(count($data) == 0);
        });
    }
}