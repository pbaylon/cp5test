<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Model\Table\BreedsTable;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Breeds Controller
 *
 * @property \App\Model\Table\BreedsTable $Breeds
 */
class BreedsController extends AppController
{
    protected BreedsTable $Breeds;
    public function initialize(): void
    {
        parent::initialize();
        $this->Breeds = $this->fetchTable('Breeds');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->disableAutoRender();
        $query = $this->Breeds->find()->where(['deleted_on IS' => null]);
        $breeds = $this->paginate($query);

        return $this->jsonResponse([
            'status' => true,
            'data' => $breeds->toArray(),
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
     * @param string|null $id Breed id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->disableAutoRender();
        $breed = $this->Breeds->get($id);
        
        
        if ($breed->deleted_on !== null) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Breed has been deleted.'
            ], 410);
        }
        return $this->jsonResponse([
            'status' => true,
            'data' => $breed
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
        $breed = $this->Breeds->newEmptyEntity();

        if($this->request->is('post')) {
            $data = $this->request->getData();
            $data['is_active'] = true; // Set default active status

            $breed = $this->Breeds->patchEntity($breed, $data);

            if ($this->Breeds->save($breed)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The breed has been saved.',
                    'breed_id' => $breed->id
                ]);
            }
            return $this->jsonResponse([
                'status' => false,
                'message'=> 'The breed could not be saved. Please, try again.',
                'errors' => $breed->getErrors()
            ], 400);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Breed id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
      $this->disableAutoRender();
      $breed = $this->Breeds->get($id);

      if($breed->deleted_on !== null) {
          return $this->jsonResponse([
              'status' => false,
              'message' => 'Breed has been deleted.'
          ], 410);
      }

     if ($this->request->is(['patch', 'post', 'put'])) {
          $data = $this->request->getData();
          $breed = $this->Breeds->patchEntity($breed, $data);

          if ($this->Breeds->save($breed)) {
              return $this->jsonResponse([
                  'status' => true,
                  'message' => 'The breed has been updated.',
                  'breed_id' => $breed->id
              ]);
          }
          return $this->jsonResponse([
              'status' => false,
              'message' => 'The breed could not be updated. Please, try again.',
              'errors' => $breed->getErrors()
          ], 400);
      }
    }

    /**
     * Delete method
     *
     * @param string|null $id Breed id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post', 'delete']);

      try{
        $breed = $this->Breeds->get($id);

        $breed->deleted_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));

           if ($this->Breeds->save($breed)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The breed has been deleted.',
                ]);
            }

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to delete the breed.'
            ], 400);

      }catch (RecordNotFoundException $error) {
          return $this->jsonResponse([
              'status' => false,
              'message' => 'Breed not found',
              'errors'=> $error->getMessage()
          ], 404);
      }
    }
}
