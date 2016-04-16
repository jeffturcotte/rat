<?php
use Rat\Identifier;

class IdentifierTest extends PHPUnit_Framework_TestCase
{
    protected $identifier;

    protected function setUp()
    {
        $this->object = new stdClass();
        $this->identifier = new Identifier();
        $this->identifier->set('test', $this->object);
    }

    public function testIdentify()
    {
        $this->assertSame($this->identifier->identify($this->object), 'test');
    }

    public function testGet()
    {
        $this->assertSame($this->identifier->get('test'), $this->object);
    }

}
