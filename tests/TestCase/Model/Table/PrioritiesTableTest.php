<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PrioritiesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PrioritiesTable Test Case
 */
class PrioritiesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PrioritiesTable
     */
    protected $Priorities;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Priorities',
        'app.Tasks',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Priorities') ? [] : ['className' => PrioritiesTable::class];
        $this->Priorities = $this->getTableLocator()->get('Priorities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Priorities);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\PrioritiesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
