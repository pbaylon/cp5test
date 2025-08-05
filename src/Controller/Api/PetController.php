<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Model\Table\PetsTable;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Pet Controller
 *
 */
class PetController extends AppController
{
    protected PetsTable $Pet;
    public function initialize():void{
        parent::initialize();
        $this->Pet = $this->fetchTable('Pets');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->disableAutoRender();
        $query = $this->Pet->find()->where(['deleted_on IS' => null]);
        $pet = $this->paginate($query);

        return $this->jsonResponse([
            'status' => true,
            'data' => $pet->toArray(),
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
     * @param string|null $id Pet id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->disableAutoRender();
        $pet = $this->Pet->get($id);

        if($pet->deleted_on !== null){
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet not found or has been deleted.',
            ], 410);
        }
        return $this->jsonResponse([
            'status' => true,
            'data' => $pet,
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
        $pet = $this->Pet->newEmptyEntity();
        
        if($this-> request->is('post')) {
            $data = $this->request->getData();
            $pet = $this->Pet->patchEntity($pet, $data);

            if($this->Pet->save($pet)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Pet has been added successfully.',
                    'pet_id' => $pet->id
                ]);
            }

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to add pet. Please try again.',
                'errors' => $pet->getErrors()
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
     * @param string|null $id Pet id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->disableAutoRender();
        $pet = $this->Pet->get($id);

        if ($pet->deleted_on !== null) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet has been deleted.'
            ], 410);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $pet = $this->Pet->patchEntity($pet, $data);
            $pet->modified_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));

            if ($this->Pet->save($pet)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The pet has been updated.',
                    'pet_id' => $pet->id
                ]);
            }
            return $this->jsonResponse([
                'status' => false,
                'message' => 'The pet could not be updated. Please, try again.',
                'errors' => $pet->getErrors()
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
     * @param string|null $id Pet id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post', 'delete']);
        
        try{
            $pet = $this->Pet->get($id);

            $pet->deleted_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));
            if ($pet->deleted_on) {
                $pet->is_deleted = true;
            }

            if($this->Pet->save($pet)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Pet has been deleted successfully.',
                ]);
            }
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to delete pet. Please try again.',
                'errors' => $pet->getErrors()
            ], 400);
        }catch(RecordNotFoundException $error){
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet not found.',
                'error' => $error->getMessage()
            ], 404);
        }
        return $this->jsonResponse([
            'status'=> false,
            'message'=> 'Invalid method.',
            ],405);
    }
}
