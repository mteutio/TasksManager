<?php
declare(strict_types=1);

namespace App\Controller;

use Error;

/**
 * Notes Controller
 *
 * @property \App\Model\Table\NotesTable $Notes
 */
class NotesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {  $CurrentUser = $this->Authentication->getResult()->getData();
        $id= $CurrentUser->id;
       $query = $this->Notes->find()->where(['Tasks.user_id'=>$id])
       ->contain(['Tasks']);
        $notes = $this->paginate($query);
        $this->set(compact('notes'));
    }

    /**
     * View method
     *
     * @param string|null $id Note id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {  
        $note = $this->Notes->get($id, contain: ['Tasks']);
        $this->set(compact('note'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($taskid=null)
    {
         try{
            $task= $this->Notes->Tasks->get($taskid);
         } catch(\cake\Datasource\Exception\RecordNotFoundException $e){
            $this->Flash->error('task doesnt exist');
            return $this->redirect(['controller'=>'Tasks', 'action'=>'index']);
         }

        $note = $this->Notes->newEmptyEntity();
        if ($this->request->is('post')) {
            $note = $this->Notes->patchEntity($note, $this->request->getData());
            $note->task_id= $taskid;
             //debug($note); die();
            if ($this->Notes->save($note)) {
                $this->Flash->success(__('The note has been saved.'));

                return $this->redirect(['controller'=>'Tasks','action' => 'view', $taskid]);
            }
            //debug($note->getErrors());
            $this->Flash->error(__('The note could not be saved. Please, try again.'));
        }
        // $tasks = $this->Notes->Tasks->find('list', limit: 200)->all();
        $this->set(compact('note','taskid'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Note id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $note = $this->Notes->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $note = $this->Notes->patchEntity($note, $this->request->getData());
            if ($this->Notes->save($note)) {
                $this->Flash->success(__('The note has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The note could not be saved. Please, try again.'));
        }
        $tasks = $this->Notes->Tasks->find('list', limit: 200)->all();
        $this->set(compact('note', 'tasks'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Note id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $note = $this->Notes->get($id);
        if ($this->Notes->delete($note)) {
            $this->Flash->success(__('The note has been deleted.'));
        } else {
            $this->Flash->error(__('The note could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller'=>'Tasks', 'action' => 'view', $note->task_id]);
    }
}
