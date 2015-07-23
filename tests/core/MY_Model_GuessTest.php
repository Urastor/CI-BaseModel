<?php

class MY_Model_GuessTest extends PHPUnit_Framework_TestCase
{

    private $model;

    public function setUp()
    {}

    public function testConstructorExecutesGuessNameMethod()
    {
        $model = new SubscribersModel(true);
        $this->assertEquals('subscribers', $model->getTableName());
    }

    public function testNoGuessIfTableNameExistsAlready()
    {
        $model = new GuestbookModel(true);
        $this->assertEquals('guest', $model->getTableName());
    }
}