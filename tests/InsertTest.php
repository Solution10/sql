<?php

namespace Solution10\SQL\Tests;

use PHPUnit_Framework_TestCase;
use Solution10\SQL\Insert;

class InsertTest extends PHPUnit_Framework_TestCase
{
    public function testTable()
    {
        $q = new Insert();
        $this->assertNull($q->table());
        $this->assertEquals($q, $q->table('users'));
        $this->assertEquals('users', $q->table());
    }

    public function testValues()
    {
        $q = new Insert();
        $this->assertEquals([], $q->values());
        $this->assertEquals($q, $q->values([
            'name' => 'Alex',
            'city' => 'London'
        ]));
        $this->assertEquals([
            'name' => 'Alex',
            'city' => 'London'
        ], $q->values());
    }

    public function testValue()
    {
        $q = new Insert();
        $this->assertEquals(null, $q->value('name'));
        $this->assertEquals($q, $q->value('name', 'Alex'));
        $this->assertEquals('Alex', $q->value('name'));
    }

    public function testBasicInsertSQL()
    {
        $q = new Insert();
        $q
            ->table('users')
            ->values(['name' => 'Alex', 'city' => 'London'])
        ;

        $this->assertEquals('INSERT INTO "users" ("name", "city") VALUES (?, ?)', (string)$q);
        $this->assertEquals(['Alex', 'London'], $q->params());
    }

    public function testResetInsert()
    {
        $q = new Insert();
        $q
            ->table('users')
            ->values(['name' => 'Alex', 'city' => 'London'])
        ;

        $this->assertEquals($q, $q->reset());
        $this->assertEquals('', (string)$q);
        $this->assertEquals([], $q->values());
    }

    public function testAllTablesReferenced()
    {
        $q = new Insert();
        $this->assertEquals([], $q->allTablesReferenced());

        $q = new Insert();
        $q->table('users');
        $this->assertEquals(['users'], $q->allTablesReferenced());

        $q = new Insert();
        $q->table('users');
        $q->table('locations');
        $this->assertEquals(['locations'], $q->allTablesReferenced());
    }
}
