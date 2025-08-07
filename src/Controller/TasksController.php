<?php
declare(strict_types=1);

namespace App\Controller;

use function PHPUnit\Framework\isNull;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 */
class TasksController extends AppController
{
  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {   
    
    // 1. Tâches créées par l'utilisateur connecté
    $createdTasksQuery = $this->Tasks->find()
        ->where([
            'Tasks.user_id' => $this->currentUser->id,
            'Tasks.deleted' => false
        ])
        ->contain(['Users', 'Priorities', 'ParentTasks'])
        ->order(['Tasks.created' => 'DESC']);

    // 2. Tâches assignées à l'utilisateur connecté mais créées par d'autres
    $assignedTasksQuery = $this->Tasks->find()
        ->where([
            'Tasks.assigned_to' => $this->currentUser->id,
            'Tasks.user_id !=' => $this->currentUser->id,
            'Tasks.deleted' => false
        ])
        ->contain(['Users', 'Priorities', 'ParentTasks'])
        ->order(['Tasks.created' => 'DESC']);

    // Pagination séparée
    $this->paginate = [
        'limit' => 10
    ];

    $createdTasks = $this->paginate($createdTasksQuery, ['scope' => 'created']);
    $assignedTasks = $this->paginate($assignedTasksQuery, ['scope' => 'assigned']);

    $this->set(compact('createdTasks', 'assignedTasks'));
    
  }

  /**
   * View method
   *
   * @param string|null $id Task id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $task = $this->Tasks->get($id, contain: ['Priorities', 
                                                'ParentTasks.Priorities', 
                                                'Notes', 
                                                'Reminders',  
                                                'ChildTasks.Priorities']);
    // debug($task); die();
    $this->set(compact('task'));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  public function add()
  {    
    $task = $this->Tasks->newEmptyEntity();
    $task->status='pending';
        
    $priorities = $this->Tasks->Priorities->find('list', keyField:'id',valueField:'name')
                        ->where([
                            'OR' => [
                                'user_id IS' => null,
                                'user_id' => $this->currentUser->id
                            ]
                        ])
                        ->toArray();

    $parentTasks = $this->Tasks->find('list', valueField:'name') ->where(['user_id'=>$this->currentUser->id]);
    
    if ($this->request->is('post')) {
      $task = $this->Tasks->patchEntity($task, $this->request->getData());
      //debug($task->getErrors());
      $task->user_id = $this->currentUser->id; // affecter la tâche à l'utilisateur actuellement connecté
      if ($this->Tasks->save($task)) {
        $this->Flash->success(__('La tâche {0} a été enregistrée avec succès !', $task->name));
        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The task could not be saved. Please, try again.'));   
    }
    $this->set(compact('task', 'priorities', 'parentTasks'));
  }

  /**
   * Edit method
   *
   * @param string|null $id Task id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $task = $this->Tasks->get($id, contain: []);
    if ($this->request->is(['patch', 'post', 'put'])) {
        $task = $this->Tasks->patchEntity($task, $this->request->getData());
        if ($this->Tasks->save($task)) {
            $this->Flash->success(__('The task has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The task could not be saved. Please, try again.'));
    }
    $users = $this->Tasks->Users->find('list', limit: 200)->all();
    $priorities = $this->Tasks->Priorities->find('list', limit: 200)->all();
    $parentTasks = $this->Tasks->ParentTasks->find('list', limit: 200)->all();
    $this->set(compact('task', 'users', 'priorities', 'parentTasks'));
  }

  /**
   * markAsDone method
   * Mark a task as Done
   * @param int $id Unique Identifier of a task
   */
  public function markAsDone($taskId=null) 
  {
        
    //$task =  $this->Tasks->findById($id)->first();
    // sélectionne la tâche sur la base son id mais en s'assurant qu'elle appartient bien à l'utilisateur connecté
    $task = $this->Tasks->find()->where(['id'=>$taskId, 'create_uid'=>$this->currentUser->id])->first();

    // vérifie qu'on a bien retrouvé la tâche dans la sélection
    if (is_null($task) || empty($task)) {
      $this->Flash->error(__('La tâche sélectionnée n\'existe pas ou a été supprimée !'));
      return $this->redirect('/');  // renvoi l'utilisateur à la page d'accueil de l'application
    }

    // Marque la tâche comme exécutée
    $task->done_at =('d_m_Y H:i:s');
    $task->status = "Done";

    // enregistre les modifications
    if ($this->Tasks->save($task)) {
      $this->Flash->success(__('your task has successfully being marked as  terminated.'));
    } else {
      $this->Flash->error(__('The task could not be marked as  terminated. Please, try again.'));      
    }
    return $this->redirect($this->referer());   // renvoi l'utilisateur vers la page d'où il est venu !
  }


 public function assign($id) {
           
          // $task =  $this->Tasks->get($id, ['contain'=>['Users']]);//collect data from the task and the users informations
           //debug($task); die();
           $users = $this->Tasks->Users->find('list')->all();
          // debug($users); die();
            $task =  $this->Tasks->get($id);
            if ($this->request->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());

            if ($this->Tasks->save($task)) {
                $this->Flash->success(__('The task has been successfully assigned.'));
//debug($task); die();
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The task could not be successfully assigned. Please, try again.'));
        }
$this->set(compact('task','users'));
           }
            

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
         $currentUser = $this->Authentication->getResult()->getData();
        $this->currentUser->id = $currentUser->id;
        $task = $this->Tasks->find()
        ->where([
            'id'=>$id,
            'user_id'=>$this->currentUser->id,
            'deleted'=>false
        ])
        ->first();


        if (!$task) {
            $this->Flash->error(__('The task could not be found.'));
             return $this->redirect(['action' => 'index']);
        } 
       $task->deleted=true;
       $task->deleted_at=new \DateTime();

       if ($this->Tasks->save($task)) {
        $this->Flash->success(__('task was placed in the bin'));
       }else {
       $this->Flash->error(__('task could not be placed in the bin'));
       }
       return $this->redirect(['action' => 'index']);
    }

    public function restore($id){
        $this->request->allowMethod(['post']);
        $currentUser = $this->Authentication->getResult()->getData();
        $this->currentUser->id = $currentUser->id;
        $task= $this->Tasks->find()
        ->where([
            'id'=>$id,
            'user_id'=>$this->currentUser->id,
            'deleted'=> true
        ])
        ->first();
        
        if (!$task) {
            $this->Flash->error(__('The task could not be found.'));
             return $this->redirect(['action' => 'index']);
        } 
       $task->deleted=false;
       $task->deleted_at=null;

       if ($this->Tasks->save($task)) {
        $this->Flash->success(__('task was successfuly restored'));
       }else {
       $this->Flash->error(__('task could not be restored'));
       }
        return $this->redirect(['action' => 'trash']);
    }

     public function deleteForever($id=null){
        $this->request->allowMethod(['post','delete']);
        $currentUser = $this->Authentication->getResult()->getData();
        $this->currentUser->id = $currentUser->id;
        $task= $this->Tasks->find()
        ->where([
            'id'=>$id,
            'user_id'=>$this->currentUser->id,
            'deleted'=> true
        ])
        ->first();
        
        if (!$task) {
            $this->Flash->error(__('The task could not be found in the bin.'));
             return $this->redirect(['action' => 'trash']);
        } 

       if ($this->Tasks->delete($task)) {
        $this->Flash->success(__('task was definitely deleted'));
       }else {
       $this->Flash->error(__('task could not be definitely deleted'));
       }
        return $this->redirect(['action' => 'trash']);
    }

    public function emptyTrash(){
        $this->request->allowMethod(['post']);
        $currentUser = $this->Authentication->getResult()->getData();
        $this->currentUser->id = $currentUser->id;
        $deletedCount = $this->Tasks->deleteAll([
            'user_id'=>$this->currentUser->id,
            'deleted'=> false
        ]);
        
        if (!$deletedCount>0) {
            $this->Flash->success(__('bin was successfully emptied from. {0} tasks.',$deletedCount));
        } else {
       $this->Flash->info(__('the bin is currently empty'));
       }
        return $this->redirect(['action' => 'trash']);
    }


   public function trash()
    {
        $users = $this->Tasks->Users->find('list', limit: 200)->all();
        $currentUser = $this->Authentication->getResult()->getData();
        $this->currentUser->id = $currentUser->id;
        $query = $this->Tasks->find()
        ->where(['Tasks.user_id'=>$this->currentUser->id,
                  'Tasks.deleted'=>true
                ])
        ->contain(['Users','Priorities','ParentTasks']);
        // debug($query);die();
       $deletedTasks = $this->paginate($query);
       $this->set(compact('deletedTasks'));
    }
}
