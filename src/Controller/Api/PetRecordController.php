<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use App\Model\Table\PetRecordsTable;

/**
 * PetRecord Controller
 *
 */
class PetRecordController extends AppController
{
    protected PetRecordsTable $PetRecord;

    public function initialize (): void{
        parent::initialize();
        $this->PetRecord = $this->fetchTable('PetRecords');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->disableAutoRender();
        $query = $this->PetRecord->find()->where(['deleted_on IS' => null]);
        $petRecord = $this->paginate($query);

        return $this->jsonResponse([
            'status' => true,
            'data' => $petRecord->toArray(),
        ]);
    }

    private function jsonResponse(array $data, int $status = 200): \Cake\Http\Response
    {
        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }

    /**
     * View method
     *
     * @param string|null $id Pet Record id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->disableAutoRender();
        $petRecord = $this->PetRecord->get($id);
        
        if($petRecord->deleted_on !== null){
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet Record not found or has been deleted.',
            ], 410);
        }
        return $this->jsonResponse([
            'status' => true,
            'data' => $petRecord,
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->disableAutoRender();
        $petRecord = $this->PetRecord->newEmptyEntity();

        if ($this->request->is('post')) {
            $petRecord = $this->PetRecord->patchEntity($petRecord, $this->request->getData());
            if ($this->PetRecord->save($petRecord)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Pet Record has been added successfully.',
                    'petRecord_id' => $petRecord->id,
                ]);
            }
           return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet Record could not be saved. Please, try again.',
                'errors' => $petRecord->getErrors(),
            ], 400);
        }
        return $this->jsonResponse([
            'status' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pet Record id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->disableAutoRender();
        $petRecord = $this->PetRecord->get($id);

        if($petRecord->deleted_on !== null){
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet Record not found or has been deleted.',
            ], 410);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $petRecord = $this->PetRecord->patchEntity($petRecord, $data);
            $petRecord->modified_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));

            if ($this->PetRecord->save($petRecord)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Pet Record has been updated successfully.',
                    'petRecord_id' => $petRecord->id,
                ]);
            }
           return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet Record could not be updated. Please, try again.',
                'errors' => $petRecord->getErrors(),
            ], 400);
        }
        return $this->jsonResponse([
            'status' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pet Record id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post', 'delete']);

       try{
        $petRecord = $this->PetRecord->get($id);
        $petRecord->deleted_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));

        if($this->$petRecord->deleted_on){
            $petRecord->is_deleted = true;
        }
        if ($this->PetRecord->save($petRecord)) {
            return $this->jsonResponse([
                'status'=> true,
                'message' => 'Pet Record has been deleted successfully.',
                'petRecord_id' => $petRecord->id,
            ]);
       }
       return $this->jsonResponse([
            'status' => false,
            'message' => 'Pet Record could not be deleted. Please, try again.',
            'errors' => $petRecord->getErrors(),
        ], 400);
       } catch (RecordNotFoundException $e) {
           return $this->jsonResponse([
               'status' => false,
               'message' => 'Pet Record not found.',
           ], 404);
       }
       return $this->jsonResponse([
            'status' => false,
            'message' => 'Invalid request method.',
        ], 405);
    }
}
