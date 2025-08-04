<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InstallationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InstallationsTable Test Case
 */
class InstallationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InstallationsTable
     */
    protected $Installations;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Installations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Installations') ? [] : ['className' => InstallationsTable::class];
        $this->Installations = $this->getTableLocator()->get('Installations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Installations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\InstallationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
