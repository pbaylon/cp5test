<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\Model\Table\PetOwnersTable;
use Cake\Http\Response;

/**
 * PetOwner Controller
 *
 */
class PetOwnerController extends BaseApiController
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
    public function index(): Response
    {
        $petOwner = $this->PetOwner->fetchAllActivePetOwners();
        return $this->json($petOwner);
    }
    /**
     * View method
     *
     * @param string|null $id Pet Owner id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id): Response
    {
        $id = (int)$id;
        $petOwner = $this->PetOwner->fetchPetOwnerById($id);
        return $this->json($petOwner ? $petOwner->jsonSerialize(): []);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(): Response
    {
        $this->request->allowMethod('post');

        $data = $this->request->getData();
        $result = $this->PetOwner->createPetOwner($data);

        return $this->json($result, $result['success'] ? 201 : 400);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pet Owner id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id): Response
    {
        $this->request->allowMethod(['put', 'patch']);

        $id = (int)$id;
        $data = $this->request->getData();
        $result = $this->PetOwner->updatePetOwner($id, $data);

        return $this->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pet Owner id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id): Response
    {
        $this->request->allowMethod('delete');
        $id = (int)$id;
        $result = $this->PetOwner->softDeletePetOwner($id);

        return $this->json($result, $result['success'] ? 200 : 400);
    }
}
