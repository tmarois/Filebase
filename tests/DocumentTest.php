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
    * (5) Test that we can run a DEFAULT value on GET request
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

        $this->assertEquals('mydefault', $doc->get('checkvalue','mydefault'));
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
    * testDocumentGetNoCollectionError()
    *
    * TEST:
    * (1) Test if we get an ERROR when trying to access collection methods
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
    * testDocumentGetDotNotation()
    *
    * TEST:
    * (1) Test that we can grab "DOT" notation from GET (collection)
    * (2) Test we can get the multi-array without collection
    *
    */
    public function testDocumentGetDotNotation()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile');
        $doc->us = ['nc'=>'charlotte'];
        $doc->save();

        $place = $doc->get('us.nc');

        // check that "DOT" notation works (collection)
        $this->assertEquals('charlotte', $place);


        // check without collection
        $doc = $db->document('profile',false);
        $place = $doc->us['nc'];

        $this->assertEquals('charlotte', $place);
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
    * testDocumentDeleteReadOnly()
    *
    * TEST:
    * (1) Test ERROR on DELETE document with READ ONLY MODE
    *
    */
    public function testDocumentDeleteReadOnly()
    {
        $this->expectException(Exception::class);

        $db = new Database([
            'path' => __DIR__.'/database',
            'readOnly' => true
        ]);

        $doc = $db->document('profile');

        $doc->delete();
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
    * (2) Test the document can be returend as JSON using toJson()
    * (3) Test the document can be returend as JSON using toJson() without collection
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
        $this->assertEquals('{"productId":123,"productName":"Apple Watch"}', $doc->toJson());

        $doc = $db->document('product1',false);

        $this->assertEquals('{"productId":123,"productName":"Apple Watch"}', $doc->toJson());

        $doc->delete();
    }


    /**
    * testDocumentOutputAsArray()
    *
    * TEST:
    * (1) Test that docuement can be returned as an ARRAY (toArray())
    * (2) Test that docuement can be returned as an ARRAY (all()) collection method
    * (3) Test that docuement can be returned as an ARRAY (toArray()) without collection
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

        $doc = $db->document('product1',false);

        $this->assertEquals(['productId'=>123,'productName'=>'Apple Watch'], $doc->toArray());

        $doc->delete();
    }


}
