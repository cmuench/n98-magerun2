<?php

namespace N98\Util\Console\Helper;

use InvalidArgumentException;
use N98\Magento\Command\TestCase;
use RuntimeException;

/**
 * Class DatabaseHelperTest
 *
 * @covers \N98\Util\Console\Helper\DatabaseHelper
 */
class DatabaseHelperTest extends TestCase
{
    /**
     * @return DatabaseHelper
     */
    protected function getHelper(): DatabaseHelper
    {
        $helperSet = $this->getApplication()->getHelperSet();

        /** @var DatabaseHelper $dbHelper */
        $dbHelper = $helperSet->get('database');

        return $dbHelper;
    }

    /**
     * @test
     */
    public function testHelperInstance()
    {
        $this->assertInstanceOf('\N98\Util\Console\Helper\DatabaseHelper', $this->getHelper());
    }

    /**
     * @test
     */
    public function getConnection()
    {
        $this->assertInstanceOf('\PDO', $this->getHelper()->getConnection());
    }

    /**
     * @test
     */
    public function dsn()
    {
        $this->assertStringStartsWith('mysql:', $this->getHelper()->dsn());
    }

    /**
     * @test
     */
    public function mysqlUserHasPrivilege()
    {
        $this->assertTrue($this->getHelper()->mysqlUserHasPrivilege('SELECT'));
    }

    /**
     * @test
     */
    public function getMysqlVariableValue()
    {
        $this->markTestSkipped('skipped');
        $helper = $this->getHelper();

        // verify (complex) return value with existing global variable
        $actual = $helper->getMysqlVariableValue('version');

        $this->assertIsArray($actual);
        $this->assertCount(1, $actual);
        $key = '@@version';
        $this->assertArrayHasKey($key, $actual);
        $this->assertIsString($actual[$key]);

        // quoted
        $actual = $helper->getMysqlVariableValue('`version`');
        $this->assertEquals('@@`version`', key($actual));

        // non-existent global variable
        try {
            $helper->getMysqlVariableValue('nonexistent');
            $this->fail('An expected exception has not been thrown');
        } catch (RuntimeException $e) {
            $this->assertEquals("Failed to query mysql variable 'nonexistent'", $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getMysqlVariable()
    {
        $helper = $this->getHelper();

        // behaviour with existing global variable
        $actual = $helper->getMysqlVariable('version');
        $this->assertIsString($actual);

        // behavior with existent session variable (INTEGER)
        $helper->getConnection()->query('SET @existent = 14;');
        $actual = $helper->getMysqlVariable('existent', '@');
        $this->assertSame("14", $actual);

        // behavior with non-existent session variable
        $actual = $helper->getMysqlVariable('nonexistent', '@');
        $this->assertNull($actual);

        // behavior with non-existent global variable
        try {
            $helper->getMysqlVariable('nonexistent');
            $this->fail('An expected Exception has not been thrown');
        } catch (RuntimeException $e) {
            // test against the mysql error message
            // unknown system variable error -> different exception message MySQL vs. MariaDB
            // so we test only that the error code is included
            $this->assertStringContainsString(
                "1193",
                $e->getMessage()
            );
        }

        // invalid variable type
        try {
            $helper->getMysqlVariable('nonexistent', '@@@');
            $this->fail('An expected Exception has not been thrown');
        } catch (InvalidArgumentException $e) {
            // test against the mysql error message
            $this->assertEquals(
                'Invalid mysql variable type "@@@", must be "@@" (system) or "@" (session)',
                $e->getMessage()
            );
        }
    }

    /**
     * @test
     */
    public function getTables()
    {
        $helper = $this->getHelper();

        $tables = $helper->getTables();
        $this->assertIsArray($tables);
        $this->assertContains('admin_user', $tables);
    }

    /**
     * @test
     */
    public function resolveTables()
    {
        $tables = $this->getHelper()->resolveTables(['catalog_*']);
        $this->assertContains('catalog_product_entity', $tables);
        $this->assertNotContains('catalogrule', $tables);

        $definitions = [
            'catalog_glob' => ['tables' => ['catalog_*']],
            'config_glob'  => ['tables' => ['core_config_dat?']],
            'directory'    => ['tables' => ['directory_country directory_country_format']],
        ];

        $tables = $this->getHelper()->resolveTables(
            ['@catalog_glob', '@config_glob', '@directory'],
            $definitions
        );
        $this->assertContains('catalog_product_entity', $tables);
        $this->assertContains('core_config_data', $tables);
        $this->assertContains('directory_country', $tables);
        $this->assertNotContains('catalogrule', $tables);
    }
}
