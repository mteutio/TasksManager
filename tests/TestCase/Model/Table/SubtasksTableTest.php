<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubtasksTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubtasksTable Test Case
 */
class SubtasksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SubtasksTable
     */
    protected $Subtasks;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Subtasks',
        'app.Users',
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
        $config = $this->getTableLocator()->exists('Subtasks') ? [] : ['className' => SubtasksTable::class];
        $this->Subtasks = $this->getTableLocator()->get('Subtasks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Subtasks);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\SubtasksTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\SubtasksTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
