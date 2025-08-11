<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Http\Response;
use App\Controller\Api\BaseApiController;
use App\Model\Table\ClientsTable;

class ClientController extends BaseApiController
{
    protected ClientsTable $Clients;
    public function initialize(): void
    {
        parent::initialize();
        $this->Clients = $this->fetchTable('Clients');
    }
    public function index(): Response
    {
        $clients = $this->Clients->fetchAllActiveClients();
        return $this->json($clients);
    }

    public function view($id): Response
    {
        $id = (int)$id;
        $client = $this->Clients->fetchClientById($id);
        return $this->json($client ? $client->jsonSerialize(): []);
    }

    public function add(): Response
    {
        $this->request->allowMethod('post');

        $data = $this->request->getData();
        $result = $this->Clients->createClient($data);

        return $this->json($result, $result['success'] ? 201 : 400);
    }

    public function edit($id): Response
    {
        $this->request->allowMethod(['put', 'patch']);

        $id = (int)$id;
        $data = $this->request->getData();
        $result = $this->Clients->updateClient($id, $data);

        return $this->json($result, $result['success'] ? 200 : 400);
    }

    public function delete($id): Response
    {
        $this->request->allowMethod('delete');
        $id = (int)$id;
        $result = $this->Clients->softDeleteClient($id);

        return $this->json($result, $result['success'] ? 200 : 400);
    }
}
