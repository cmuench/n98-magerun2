<?php
/**
 * This file is part of the n98-magerun2 project.
 *
 * For the full copyright and license information, please view the MIT-LICENSE.txt
 * file that was distributed with this source code.
 */

namespace N98\Magento\Command\System\Email;

use N98\Magento\Command\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class TestCommandTest extends TestCase
{
    public function testMissingToOptionFailsWithoutPromptingOrSendingMail()
    {
        // Run with interactive=false so the "to" prompt resolves to its empty default instead
        // of reading from stdin. This guarantees the command fails validation before it ever
        // reaches the mail transport, regardless of the test runner's stdin/TTY state.
        $tester = new CommandTester($this->getApplication()->find('sys:email:test'));
        $status = $tester->execute([], ['interactive' => false]);

        $this->assertSame(1, $status);
        $this->assertStringContainsString(
            'Please provide a valid recipient email address with --to',
            $tester->getDisplay()
        );
    }

    public function testInvalidToOptionFails()
    {
        $this->assertDisplayContains(
            ['command' => 'sys:email:test', '--to' => 'not-an-email'],
            'Please provide a valid recipient email address with --to'
        );
    }

    public function testInvalidFromOptionFails()
    {
        $this->assertDisplayContains(
            ['command' => 'sys:email:test', '--to' => 'test@example.com', '--from' => 'not-an-email'],
            'The --from email address is not valid'
        );
    }

    public function testInvalidCcOptionFails()
    {
        $this->assertDisplayContains(
            ['command' => 'sys:email:test', '--to' => 'test@example.com', '--cc' => ['not-an-email']],
            'not-an-email" is not valid'
        );
    }
}
