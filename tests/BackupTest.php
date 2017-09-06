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


    public function testBackupCreate()
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

        $file = $db->backup()->create();

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

        $db->backup()->create();

        $backups = $db->backup()->find();

        $this->assertInternalType('array',$backups);
        $this->assertNotEmpty($backups);
    }


    public function testBackupFindSort()
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

        $db->backup()->create();
        $db->backup()->create();
        $last = str_replace('.zip','',$db->backup()->create());

        $backups = $db->backup()->find();
        $backupCurrent = current($backups);

        $lastBackup = str_replace('.zip','',basename($backupCurrent));

        $db->flush(true);

        $this->assertEquals($last,$lastBackup);
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


    public function testBackupRestore()
    {
        $db1 = new \Filebase\Database([
            'dir' => __DIR__.'/databases/backupdb',
            'backupLocation' => __DIR__.'/databases/storage/backupdb'
        ]);

        for ($x = 1; $x <= 25; $x++)
    	{
    		$user = $db1->get(uniqid());
    		$user->name = 'John';
    		$user->save();
    	}

        $db1->backup()->create();

        $items1 = $db1->count();

        $db2 = new \Filebase\Database([
            'dir' => __DIR__.'/databases/backupdb2',
            'backupLocation' => __DIR__.'/databases/storage/backupdb'
        ]);

        $db2->backup()->rollback();
        $db2->backup()->clean();

        $items2 = $db2->count();

        $db1->flush(true);
        $db2->flush(true);

        $this->assertEquals($items1,$items2);

    }


}
