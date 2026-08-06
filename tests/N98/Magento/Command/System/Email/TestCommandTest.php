<?php
/**
 * This file is part of the n98-magerun2 project.
 *
 * For the full copyright and license information, please view the MIT-LICENSE.txt
 * file that was distributed with this source code.
 */

namespace N98\Magento\Command\System\Email;

use N98\Magento\Command\TestCase;

class TestCommandTest extends TestCase
{
    public function testMissingToOptionFails()
    {
        $this->assertDisplayContains(
            ['command' => 'sys:email:test'],
            'Please provide a valid recipient email address with --to'
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
