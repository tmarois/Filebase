<?php

namespace Filebase;

class DocumentTest extends \PHPUnit\Framework\TestCase
{

    public function testDocumentSave()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile');
        // use the collection->set()
        $doc->set('name','John');
        // define the property directly
        $doc->topic = 'php';
        // save the file
        $doc->save();

        $this->assertEquals('profile', $doc->getName());

        $this->assertEquals('John', $doc->name);
        $this->assertEquals('John', $doc->get('name'));

        $this->assertEquals('php', $doc->topic);
        $this->assertEquals('php', $doc->get('topic'));
    }


    public function testDocumentGet()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile');

        $this->assertEquals('John', $doc->name);
        $this->assertEquals('John', $doc->get('name'));

        $this->assertEquals('php', $doc->topic);
        $this->assertEquals('php', $doc->get('topic'));
    }


    public function testDocumentDelete()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile');

        $doc->delete();

        $doc = $db->document('profile');

        $this->assertEquals([], $doc->all());
    }


    public function testDocumentBadName()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('testing 123');
        $doc->save();
        $doc->delete();

        $this->assertEquals('testing 123', $doc->getName());
        $this->assertRegExp('/testing123.db$/', $doc->getPath());
    }


    public function testDocumentOutputAsJSON()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('product1');
        $doc->productId = 123;
        $doc->productName = 'Apple Watch';
        $doc->save();

        // check that we can output the whole doc as a string JSON
        $this->assertEquals('{"productId":123,"productName":"Apple Watch"}', $doc);

        $doc->delete();
    }


    public function testDocumentOutputAsArray()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('product1');
        $doc->productId = 123;
        $doc->productName = 'Apple Watch';
        $doc->save();

        $this->assertEquals(['productId'=>123,'productName'=>'Apple Watch'], $doc->toArray());
        $this->assertEquals(['productId'=>123,'productName'=>'Apple Watch'], $doc->all());

        $doc->delete();
    }


}
