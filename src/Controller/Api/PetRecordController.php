<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\Model\Table\PetRecordsTable;
use Cake\Http\Response;

/**
 * PetRecord Controller
 *
 */
class PetRecordController extends BaseApiController
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
    public function index(): Response
    {
       $petRecord = $this->PetRecord->fetchAllActivePetRecords();
       return $this->json($petRecord);
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
        $id = (int)$id;
        $petRecord = $this->PetRecord->fetchPetRecordById($id);
        return $this->json($petRecord ? $petRecord->jsonSerialize(): []);
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
        $result = $this->PetRecord->createPetRecord($data);

        return $this->json($result, $result['success'] ? 201 : 400);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pet Record id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id): Response
    {
       $this->request->allowMethod(['put', 'patch']);

        $id = (int)$id;
        $data = $this->request->getData();
        $result = $this->PetRecord->updatePetRecord($id, $data);

        return $this->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pet Record id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id): Response
    {
        $this->request->allowMethod('delete');
        $id = (int)$id;
        $result = $this->PetRecord->softDeletePetRecord($id);

        return $this->json($result, $result['success'] ? 200 : 400);
    }
}
