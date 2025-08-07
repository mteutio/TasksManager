<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Reminders Controller
 *
 * @property \App\Model\Table\RemindersTable $Reminders
 */
class RemindersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {   
        $CurrentUser = $this->Authentication->getResult()->getData();
        $id = $CurrentUser->id;
        $query = $this->Reminders->find()
            ->contain(['Tasks'])
            ->where(['Tasks.user_id' => $id]);
        $reminders = $this->paginate($query);

        $this->set(compact('reminders'));
    }

    /**
     * View method
     *
     * @param string|null $id Reminder id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reminder = $this->Reminders->get($id, contain: ['Tasks']);
        $this->set(compact('reminder'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reminder = $this->Reminders->newEmptyEntity();

         $taskId = $this->request->getQuery('task_id');
    if ($taskId) {
        $reminder->task_id = $taskId; // pre-fill it
    }

        if ($this->request->is('post')) {
            $reminder = $this->Reminders->patchEntity($reminder, $this->request->getData());
            if ($this->Reminders->save($reminder)) {
                $this->Flash->success(__('The reminder has been saved.'));

                return $this->redirect(['controller'=>'Tasks','action' => 'index']);
            }
            $this->Flash->error(__('The reminder could not be saved. Please, try again.'));
        }
        $tasks = $this->Reminders->Tasks->find('list', limit: 200)->all();
        $this->set(compact('reminder', 'tasks'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Reminder id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reminder = $this->Reminders->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reminder = $this->Reminders->patchEntity($reminder, $this->request->getData());
            if ($this->Reminders->save($reminder)) {
                $this->Flash->success(__('The reminder has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reminder could not be saved. Please, try again.'));
        }
        $tasks = $this->Reminders->Tasks->find('list', limit: 200)->all();
        $this->set(compact('reminder', 'tasks'));
         return $this->redirect(['action' => 'tasks']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Reminder id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reminder = $this->Reminders->get($id);
        if ($this->Reminders->delete($reminder)) {
            $this->Flash->success(__('The reminder has been deleted.'));
        } else {
            $this->Flash->error(__('The reminder could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller'=>'Tasks', 'action' => 'view', $reminder->task_id]);
    }
}
