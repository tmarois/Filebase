<?php  namespace Filebase;

class BackupTest extends \PHPUnit\Framework\TestCase
{

    public function testBackupLocationCustom()
    {
        $db = new \Filebase\Database([
            'dir'            => __DIR__.'/databases/mydatabasetobackup',
            'backupLocation' => __DIR__.'/databases/storage/backups'
        ]);

        $db->backup();

        $this->assertEquals(true, true);
    }


    public function testBackupLocationDefault()
    {

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/mydatabasetobackup'
        ]);

        $db->backup();

        $this->assertEquals(true, true);
    }


    public function testBackupSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/mydatabasetobackup',
            'backupLocation' => __DIR__.'/databases/storage/backups'
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 25; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name = 'John';
    		$user->save();
    	}

        $file = $db->backup()->save();

        $db->flush(true);

        $this->assertRegExp('/[0-9]+\.zip$/',$file);
    }


    public function testBackupFind()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/mydatabasetobackup',
            'backupLocation' => __DIR__.'/databases/storage/backups'
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 25; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name = 'John';
    		$user->save();
    	}

        $db->backup()->save();

        $backups = $db->backup()->find();

        $this->assertInternalType('array',$backups);
        $this->assertNotEmpty($backups);
    }


    public function testBackupCleanup()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/mydatabasetobackup',
            'backupLocation' => __DIR__.'/databases/storage/backups'
        ]);

        $backupBefore = $db->backup()->find();

        $db->backup()->clean();
        $backupAfter = $db->backup()->find();

        $this->assertInternalType('array',$backupBefore);
        $this->assertNotEmpty($backupBefore);

        $this->assertInternalType('array',$backupAfter);
        $this->assertEmpty($backupAfter);
    }

}
