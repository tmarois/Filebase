<?php

namespace Filebase;

class DocumentTest extends \PHPUnit\Framework\TestCase
{

    public function testDocumentProperties()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile');
        $doc->set('name','John');
        $doc->save();

        $this->assertEquals('John', $doc->name);

        $this->assertEquals('John', $doc->get('name'));
    }


    public function testDocumentSave()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('profile');

        $this->assertEquals('John', $doc->name);

        $this->assertEquals('John', $doc->get('name'));
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

}
