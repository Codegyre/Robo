<?php

use PHPUnit\Framework\TestCase;

class RsyncTest extends TestCase
{
    // tests
    public function testRsync()
    {
        $linuxCmd = "rsync --recursive --exclude '.git' --exclude '.svn' --exclude '.hg' --checksum --whole-file --verbose --progress --human-readable --stats 'src/' 'dev@localhost:/var/www/html/app/'";

        $winCmd = 'rsync --recursive --exclude .git --exclude .svn --exclude .hg --checksum --whole-file --verbose --progress --human-readable --stats "src/" "dev@localhost:/var/www/html/app/"';

        $cmd = stripos(PHP_OS, 'WIN') === 0 ? $winCmd : $linuxCmd;

        $this->assertEquals(
            $cmd,
            (new \Robo\Task\Remote\Rsync())
                ->fromPath('src/')
                ->toHost('localhost')
                ->toUser('dev')
                ->toPath('/var/www/html/app/')
                ->recursive()
                ->excludeVcs()
                ->checksum()
                ->wholeFile()
                ->verbose()
                ->progress()
                ->humanReadable()
                ->stats()
                ->getCommandDescription()
        );

        $linuxCmd = 'rsync \'src/foo bar/baz\' \'dev@localhost:/var/path/with/a space\'';

        $winCmd = 'rsync "src/foo bar/baz" "dev@localhost:/var/path/with/a space"';

        $cmd = stripos(PHP_OS, 'WIN') === 0 ? $winCmd : $linuxCmd;

        // From the folder 'foo bar' (with space) in 'src' directory
        $this->assertEquals(
            $cmd,
            (new \Robo\Task\Remote\Rsync())
                ->fromPath('src/foo bar/baz')
                ->toHost('localhost')
                ->toUser('dev')
                ->toPath('/var/path/with/a space')
                ->getCommandDescription()
        );

        $linuxCmd = "rsync 'src/foo' 'src/bar' 'dev@localhost:/var/path/with/a space'";

        $winCmd = 'rsync "src/foo" "src/bar" "dev@localhost:/var/path/with/a space"';

        $cmd = stripos(PHP_OS, 'WIN') === 0 ? $winCmd : $linuxCmd;

        // Copy two folders, 'src/foo' and 'src/bar'
        $this->assertEquals(
            $cmd,
            (new \Robo\Task\Remote\Rsync())
                ->fromPath(['src/foo', 'src/bar'])
                ->toHost('localhost')
                ->toUser('dev')
                ->toPath('/var/path/with/a space')
                ->getCommandDescription()
        );

        $linuxCmd = "rsync --rsh 'ssh -i ~/.ssh/id_rsa' 'src/foo' 'dev@localhost:/var/path'";

        $winCmd = 'rsync --rsh "ssh -i ~/.ssh/id_rsa" "src/foo" "dev@localhost:/var/path"';

        $cmd = stripos(PHP_OS, 'WIN') === 0 ? $winCmd : $linuxCmd;

        // rsync with a remoteShell specified
        $this->assertEquals(
            $cmd,
            (new \Robo\Task\Remote\Rsync())
                ->fromPath('src/foo')
                ->toHost('localhost')
                ->toUser('dev')
                ->toPath('/var/path')
                ->remoteShell('ssh -i ~/.ssh/id_rsa')
                ->getCommandDescription()
        );
    }
}
