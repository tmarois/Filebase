<?php  namespace Filebase;


class ValidationTest extends \PHPUnit\Framework\TestCase
{

    public function testValidatingStringRequiredGood()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'name'   => [
                    'valid.type' => 'string',
                    'valid.required' => true
                ]
            ]
        ]);

        $db->flush(true);

        $db->get('test')->set(['name'=>'value'])->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }


    public function testStringRequiredBad()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'name'   => [
                    'valid.type' => 'string',
                    'valid.required' => true
                ]
            ]
        ]);

        $db->flush(true);

        $db->get('test')->set(['name'=>123])->save();

        $db->flush(true);
    }


    public function testOnlyRequiredGood()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'name'   => [
                    'valid.required' => true
                ]
            ]
        ]);

        $db->flush(true);

        $db->get('test')->set(['name'=>'value'])->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }


    public function testOnlyRequiredBad()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'this_is_new'   => [
                    'valid.required' => true
                ]
            ]
        ]);

        $db->flush(true);

        $db->get('test')->set(['name'=>'value'])->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }



    public function testNestedString()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'type' => 'array',
                    'aboutme'   => [
                        'valid.type' => 'string',
                        'valid.required' => true
                    ]
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => [
                'aboutme' => 'I am a happy coder'
            ]
        ];


        $db->get('test')->set($array)->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }


    public function testNestedBad()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'type' => 'array',
                    'aboutme'   => [
                        'valid.type' => 'string',
                        'valid.required' => true
                    ]
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => [
                'aboutme' => 321456
            ]
        ];


        $db->get('test')->set($array)->save();

        $db->flush(true);
    }


    public function testArrayGood()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'valid.type' => 'array'
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => [
                "1","2","3"
            ]
        ];

        $db->get('test')->set($array)->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }


    public function testArrayBad()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'valid.type' => 'array'
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => 123
        ];

        $db->get('test')->set($array)->save();

        $db->flush(true);
    }

    public function testIntBad()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'valid.type' => 'int'
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => '123'
        ];

        $db->get('test')->set($array)->save();

        $db->flush(true);
    }

    public function testIntGood()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'valid.type' => 'int'
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => 123
        ];

        $db->get('test')->set($array)->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }


    public function testArrType()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'valid.type' => 'arr'
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => [123]
        ];

        $db->get('test')->set($array)->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }


    public function testUnknownType()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'validate' => [
                'profile'   => [
                    'valid.type' => 'unknown'
                ]
            ]
        ]);

        $db->flush(true);


        $array = [
            'profile' => [123]
        ];

        $db->get('test')->set($array)->save();

        $this->assertEquals(true, true);

        $db->flush(true);
    }

}
