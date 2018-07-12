<?php namespace Filebase;

use Exception;

class DocumentTest extends \PHPUnit\Framework\TestCase
{

    /**
    * testDocumentSave()
    *
    * TEST:
    * (1) Test that we can SAVE the document
    * (2) Test that we can edit/change document properties
    * (3) Test that we can getName() of document
    * (4) Test that we can use the Collection->get()
    *
    */
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
        $this->assertInternalType('string', $doc->getPath());

        $this->assertEquals('John', $doc->name);
        $this->assertEquals('John', $doc->get('name'));

        $this->assertEquals('php', $doc->topic);
        $this->assertEquals('php', $doc->get('topic'));
    }


    /**
    * testDocumentGetWithCollection()
    *
    * TEST:
    * (1) Test that we can get the saved document (previous test)
    * (2) Test that we can use the Collection->get()
    *
    */
    public function testDocumentGetWithCollection()
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


    /**
    * testDocumentGetNoCollection()
    *
    * TEST:
    * (1) Test that we get document (without collection object)
    *
    */
    public function testDocumentGetNoCollection()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile',false);

        $this->assertInternalType('array', $doc->toArray());

        $this->assertEquals('John', $doc->name);
    }


    /**
    * testDocumentGetNoCollection()
    *
    * TEST:
    * (1) Test that we get document (without collection object)
    *
    */
    public function testDocumentGetNoCollectionError()
    {
        $this->expectException(Exception::class);

        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile',false);

        // get is a collection method...
        // this should NOT work.
        $name = $doc->get('name');

    }


    /**
    * testDocumentDelete()
    *
    * TEST:
    * (1) Test that we can DELETE document
    * (2) Test that deleted document clears current object data
    *
    */
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


    /**
    * testDocumentBadName()
    *
    * TEST:
    * (1) Test BAD document name gets fixed
    *
    */
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


    /**
    * testDocumentOutputAsJSON()
    *
    * TEST:
    * (1) Test the document can be returend as JSON when outputing
    *
    */
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


    /**
    * testDocumentOutputAsArray()
    *
    * TEST:
    * (1) Test that docuement can be returned as an ARRAY
    *
    */
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
