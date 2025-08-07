<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\Model\Table\PetsTable;
use Cake\Http\Response;

/**
 * Pet Controller
 *
 */
class PetController extends BaseApiController
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
        $pet = $this->Pet->fetchAllActivePets();
        return $this->json($pet);
    }

    /**
     * View method
     *
     * @param string|null $id Pet id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id): Response
    {
        $id = (int)$id;
        $pet = $this->Pet->fetchPetsById($id);
        return $this->json($pet ? $pet->jsonSerialize(): []);
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
       $result = $this->Pet->createPet($data);

       return $this->json($result, $result['success'] ? 201 : 400);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pet id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
       $this->request->allowMethod('put', 'patch');
      
      $id = (int)$id;
      $data = $this->request->getData();
      $result = $this->Pet->updatePet($id, $data);

      return $this->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pet id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id)
    {
       $this->request->allowMethod('delete');
        $id = (int)$id;
        $result = $this->Pet->softDeletePet($id);

        return $this->json($result, $result['success'] ? 200 : 400);
    }
}
