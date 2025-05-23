<?php

declare(strict_types=1);

namespace N98\Magento\Command\Github\PatchFileContent\Processor;

use PHPUnit\Framework\TestCase;

class AppDesignProcessorTest extends TestCase
{
    public function testProcess()
    {
        $processor = new AppDesignProcessor();

        $diffContent = 'app/design/frontend/Magento/sampletheme/';
        $expectedResult = 'vendor/magento/theme-frontend-sampletheme/';

        $this->assertSame(
            $expectedResult,
            $processor->process($diffContent, 'magento'),
        );
    }
}
