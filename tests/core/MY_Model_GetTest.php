<?php

class MY_Model_GetTest extends MY_Model_DBTestCase
{

    private $model;

    public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../database/data/subscribers.yaml');
    }

    public function setUp()
    {
        $this->model = new SubscribersModel(true);
        $this->model->db = $this->getMockBuilder('MY_Model_DB_Mock')->getMock();
    }

    public function testGet()
    {
        $this->model->db->expects($this->once())
            ->method('where')
            ->with($this->equalTo('id'), $this->equalTo(1))
            ->will($this->returnValue($this->model->db));
        
        $this->model->db->expects($this->once())
            ->method('get')
            ->with($this->equalTo('subscribers'))
            ->will($this->returnValue($this->model->db));
        
        $this->model->db->expects($this->once())
            ->method('row')
            ->with($this->equalTo(0), $this->equalTo('object'))
            ->will($this->returnValue($this->getConnection()
            ->createQueryTable('subscribers', 'SELECT * FROM subscribers WHERE id = "1"')
            ->getRow(0)));
        
        $expected = new PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../database/data/subscribers-get.yaml');
        
        $this->model->setID(1);
        $actual = $this->model->get();
        
        $this->assertEquals($expected->getTable('subscribers')
            ->getRow(0), $actual);
    }

    public function testGetByKeyValue()
    {
        $this->model->db->expects($this->once())
            ->method('where')
            ->with($this->equalTo('name'), $this->equalTo('Testor'))
            ->will($this->returnValue($this->model->db));
        
        $this->model->db->expects($this->once())
            ->method('get')
            ->with($this->equalTo('subscribers'))
            ->will($this->returnValue($this->model->db));
        
        $this->model->db->expects($this->once())
            ->method('row')
            ->with($this->equalTo(0), $this->equalTo('object'))
            ->will($this->returnValue($this->getConnection()
            ->createQueryTable('subscribers', 'SELECT * FROM subscribers WHERE name = "Testor"')
            ->getRow(0)));
        
        $expected = new PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../database/data/subscribers-get.yaml');
        
        $actual = $this->model->getBy('name', 'Testor');
        
        $this->assertEquals($expected->getTable('subscribers')
            ->getRow(0), $actual);
    }

    public function testGetByWhereString()
    {
        $this->model->db->expects($this->once())
            ->method('where')
            ->with($this->equalTo('name="Testor" AND phone="(036880) 964869"'))
            ->will($this->returnValue($this->model->db));
        
        $this->model->db->expects($this->once())
            ->method('get')
            ->with($this->equalTo('subscribers'))
            ->will($this->returnValue($this->model->db));
        
        $this->model->db->expects($this->once())
            ->method('row')
            ->with($this->equalTo(0), $this->equalTo('object'))
            ->will($this->returnValue($this->getConnection()
            ->createQueryTable('subscribers', 'SELECT * FROM subscribers WHERE name="Testor" AND phone="(036880) 964869"')
            ->getRow(0)));
        
        $expected = new PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../database/data/subscribers-get.yaml');
        
        $actual = $this->model->getBy('name="Testor" AND phone="(036880) 964869"');
        
        $this->assertEquals($expected->getTable('subscribers')
            ->getRow(0), $actual);
    }
}
