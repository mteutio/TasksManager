<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Priorities Controller
 *
 * @property \App\Model\Table\PrioritiesTable $Priorities
 */
class PrioritiesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Priorities->find();
        $priorities = $this->paginate($query);

        $this->set(compact('priorities'));
    }

    /**
     * View method
     *
     * @param string|null $id Priority id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $priority = $this->Priorities->get($id, contain: ['Tasks']);
        $priorities = $this->Priorities->find()
        ->where([
            'OR' => [
                'user_id IS' => null,
                'user_id' => $this->currentUser->id
            ]
    ])
    ->all();

        $this->set(compact('priority'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $CurrentUser = $this->Authentication->getResult()->getData();
        $id= $CurrentUser->id;
        $priority = $this->Priorities->newEmptyEntity();
        if ($this->request->is('post')) {
            $priority = $this->Priorities->patchEntity($priority, $this->request->getData());

            if ($this->Priorities->save($priority)) {
                $this->Flash->success(__('The priority has been saved.'));
                $priority->user_id = $id; // attach current user
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The priority could not be saved. Please, try again.'));
        }
        $this->set(compact('priority'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Priority id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $priority = $this->Priorities->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $priority = $this->Priorities->patchEntity($priority, $this->request->getData());
            if ($this->Priorities->save($priority)) {
                $this->Flash->success(__('The priority has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The priority could not be saved. Please, try again.'));
        }
        $this->set(compact('priority'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Priority id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $priority = $this->Priorities->get($id);
        if ($this->Priorities->delete($priority)) {
            $this->Flash->success(__('The priority has been deleted.'));
        } else {
            $this->Flash->error(__('The priority could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
