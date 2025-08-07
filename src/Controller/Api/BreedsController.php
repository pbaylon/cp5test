<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Http\Response;
use App\Controller\Api\BaseApiController;
use App\Model\Table\BreedsTable;

/**
 * Breeds Controller
 *
 * @property \App\Model\Table\BreedsTable $Breeds
 */
class BreedsController extends BaseApiController
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
        $breed = $this->Breeds->fetchAllActiveBreeds();
        return $this->json($breed);
    }
    /**
     * View method
     *
     * @param string|null $id Breed id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id): Response
    {
        $id = (int)$id;
        $breed = $this->Breeds->fetchBreedById($id);
        return $this->json($breed ? $breed->jsonSerialize(): []);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
       $this->request->allowMethod('post');

       $data = $this->request->getData();
       $result = $this->Breeds->createBreed($data);

       return $this->json($result, $result['success'] ? 201 : 400);
    }

    /**
     * Edit method
     *
     * @param string|null $id Breed id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
      $this->request->allowMethod('put', 'patch');
      
      $id = (int)$id;
      $data = $this->request->getData();
      $result = $this->Breeds->updateBreed($id, $data);

      return $this->json($result, $result['success'] ? 200 : 400);

    }

    /**
     * Delete method
     *
     * @param string|null $id Breed id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id): Response
    {
        $this->request->allowMethod('delete');
        $id = (int)$id;
        $result = $this->Breeds->softDeleteBreed($id);

        return $this->json($result, $result['success'] ? 200 : 400);
    }
}
