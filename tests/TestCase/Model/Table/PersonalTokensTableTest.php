<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PersonalTokensTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PersonalTokensTable Test Case
 */
class PersonalTokensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PersonalTokensTable
     */
    protected $PersonalTokens;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.PersonalTokens',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PersonalTokens') ? [] : ['className' => PersonalTokensTable::class];
        $this->PersonalTokens = $this->getTableLocator()->get('PersonalTokens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->PersonalTokens);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\PersonalTokensTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\PersonalTokensTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
