<?php

declare(strict_types=1);

namespace N98\Util;

use PHPUnit\Framework\TestCase;

class ProjectComposerTest extends TestCase
{
    /**
     * @var \N98\Util\ProjectComposer
     */
    private $sut;

    protected function setUp(): void
    {
        $this->sut = new ProjectComposer(__DIR__ . '/_files/sample-project/composer');
    }

    /**
     * @test
     */
    public function isLockFile()
    {
        $this->assertTrue($this->sut->isLockFile());
    }

    /**
     * @test
     */
    public function isComposerJsonFile()
    {
        $this->assertTrue($this->sut->isComposerJsonFile());
    }

    /**
     * @test
     * @throws \JsonException
     */
    public function itShouldReturnAPackageList()
    {
        $returnedPackages = $this->sut->getComposerLockPackages();

        $this->assertCount(44, $returnedPackages);
    }
}
