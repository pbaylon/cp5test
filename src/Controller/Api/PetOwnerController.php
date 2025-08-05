<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use App\Model\Table\PetOwnersTable;

/**
 * PetOwner Controller
 *
 */
class PetOwnerController extends AppController
{
    protected PetOwnersTable $PetOwner;

    public function initialize(): void
    {
        parent::initialize();
        $this->PetOwner = $this->fetchTable('PetOwners');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->disableAutoRender();
        $query = $this->PetOwner->find()->where(['deleted_on IS' => null]);
        $petOwner = $this->paginate($query);

        return $this->jsonResponse([
            'status' => true,
            'data' => $petOwner->toArray(),
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
     * @param string|null $id Pet Owner id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->disableAutoRender();
        $petOwner = $this->PetOwner->get($id);
        
        if($petOwner->deleted_on !== null){
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet Owner not found or has been deleted.',
            ], 410);
        }

        return $this->jsonResponse([
            'status' => true,
            'data' => $petOwner,
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
        $petOwner = $this->PetOwner->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $petOwner = $this->PetOwner->patchEntity($petOwner, $data);

            if ($this->PetOwner->save($petOwner)) {
               return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Pet Owner has been added successfully.',
                    'petOwner_id' => $petOwner->id
                ]);
            }
            return $this->jsonResponse([
                'status'=> false,
                'message'=> 'Failed to add Pet Owner. Please try again.',
                'errors' => $petOwner->getErrors()
            ], 400);
        }
        return $this->jsonResponse([
            'status' => false,
            'message' => 'Invalid request method. Please use POST to add a Pet Owner.'
        ], 405);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pet Owner id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->disableAutoRender();
        $petOwner = $this->PetOwner->get($id);

        if($petOwner->deleted_on !== null){
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet Owner not found or has been deleted.',
            ], 410);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $petOwner = $this->PetOwner->patchEntity($petOwner, $data);
            $petOwner->modified_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));

            if ($this->PetOwner->save($petOwner)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'Pet Owner has been updated successfully.',
                    'petOwner_id' => $petOwner->id
                ]);
            }
            return $this->jsonResponse([
                'status'=> false,
                'message'=> 'Failed to update Pet Owner. Please try again.',
                'errors' => $petOwner->getErrors()
            ], 400);
        }
        return $this->jsonResponse([
            'status' => false,
            'message' => 'Invalid request method. Please use PATCH or PUT to edit a Pet Owner.'
        ], 405);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pet Owner id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post', 'delete']);
        try {
            $petOwner = $this->PetOwner->get($id);
            $petOwner->deleted_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));
            if ($petOwner->deleted_on) {
                $petOwner->is_deleted = true;
            }

            if($this->PetOwner->save($petOwner)){
                return $this->jsonResponse([
                    'status'=> true,
                    'message'=> 'Pet Owner has been deleted successfully.',
                    'petOwner_id' => $petOwner->id
                ]);
            }
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to delete Pet Owner. Please try again.',
                'errors' => $petOwner->getErrors()
            ], 400);
        } catch (RecordNotFoundException $error) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Pet Owner not found.',
                'error' => $error->getMessage()
            ], 404);
        }
        return $this->jsonResponse([
            'status' => false,
            'message' => 'Invalid request method. Please use POST or DELETE to remove a Pet Owner.'
        ], 405); 
    }
}
