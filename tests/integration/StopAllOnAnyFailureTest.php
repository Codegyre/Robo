<?php
namespace Robo;

use PHPUnit\Framework\TestCase;
use Robo\Task\Base\Exec;
use Robo\Task\Base\ParallelExec;
use Robo\Traits\TestTasksTrait;

class StopAllOnAnyFailureTest extends TestCase
{
    use TestTasksTrait;
    use Task\File\loadTasks;

    protected $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
        $this->initTestTasksTrait();
    }

    public function tearDown()
    {
        $this->fixtures->cleanup();
    }

    public function testParallelProcessesGetStoppedIfStopAllOnAnyFailureIsSet()
    {
        // Some tests may left this to true
        Result::$stopOnFail = false;
        $this->fixtures->createAndCdToSandbox();

        $filenameOk = uniqid('ok_');
        $filenameKo = uniqid('ko_');

        /** @var ParallelExec $parallel */
        $parallel = $this->task(ParallelExec::class);
        $parallel->stopAllOnAnyFailure(true);
        $parallel->process($this->task(Exec::class, sprintf('touch %s && false', escapeshellarg($filenameOk))));
        $parallel->process($this->task(Exec::class, sprintf('sleep 3 && touch %s', escapeshellarg($filenameKo))));
        $parallel->run();

        $this->assertFileExists($filenameOk);
        $this->assertFileNotExists($filenameKo);
    }
}
