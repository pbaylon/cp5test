<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PetOwnersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PetOwnersTable Test Case
 */
class PetOwnersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PetOwnersTable
     */
    protected $PetOwners;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.PetOwners',
        'app.Pets',
        'app.Clients',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PetOwners') ? [] : ['className' => PetOwnersTable::class];
        $this->PetOwners = $this->getTableLocator()->get('PetOwners', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->PetOwners);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\PetOwnersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\PetOwnersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
