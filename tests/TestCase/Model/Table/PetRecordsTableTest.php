<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PetRecordsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PetRecordsTable Test Case
 */
class PetRecordsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PetRecordsTable
     */
    protected $PetRecords;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.PetRecords',
        'app.Pets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PetRecords') ? [] : ['className' => PetRecordsTable::class];
        $this->PetRecords = $this->getTableLocator()->get('PetRecords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->PetRecords);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\PetRecordsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\PetRecordsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
