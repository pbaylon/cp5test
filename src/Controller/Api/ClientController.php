<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController; 
use Cake\Datasource\Exception\RecordNotFoundException;
use App\Model\Table\ClientsTable;

class ClientController extends AppController
{
    protected ClientsTable $Client;

    public function initialize(): void
    {
        parent::initialize();
        $this->Client = $this->fetchTable('Clients');
    }

    public function index()
    {
        $this->disableAutoRender();
        $query = $this->Client->find()->where(['deleted_on IS' => null]);
        $client = $this->paginate($query);

        return $this->jsonResponse([
            'status' => true,
            'data' => $client->toArray(),
        ]);
    }

    private function jsonResponse(array $data, int $status = 200): \Cake\Http\Response
    {
        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }

    public function view($id = null)
    {
        $this->disableAutoRender();

        $client = $this->Client->get($id);

        if ($client->deleted_on !== null) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Client has been deleted.'
            ], 410);
        }

        return $this->jsonResponse([
            'status' => true,
            'data' => $client
        ]);
    }

    public function add()
    {
        $this->disableAutoRender();
        $client = $this->Client->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['is_active'] = true;

            $client = $this->Client->patchEntity($client, $data);

            if ($this->Client->save($client)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The client has been saved.',
                    'client_id' => $client->id
                ]);
            }

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to save client.',
                'errors' => $client->getErrors()
            ], 400);
        }

        return $this->jsonResponse([
            'status' => false,
            'message' => 'Method not allowed',
        ], 405);
    }

    public function edit($id = null)
    {
        $this->disableAutoRender();
        $client = $this->Client->get($id);

        if ($client->deleted_on !== null) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Cannot edit a deleted client.'
            ], 410);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Client->patchEntity($client, $this->request->getData());

            if ($this->Client->save($client)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The client has been updated.',
                    'client_id' => $client->id
                ]);
            }

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to update client.',
                'errors' => $client->getErrors()
            ], 400);
        }

        return $this->jsonResponse([
            'status' => false,
            'message' => 'Method not allowed',
        ], 405);
    }

    public function delete($id = null)
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post', 'delete']);

        try {
            $client = $this->Client->get($id);

            $client->deleted_on = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila'));

            if ($this->Client->save($client)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The client has been deleted.',
                ]);
            }

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to delete the client.'
            ], 400);

        } catch (RecordNotFoundException $error) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Client not found',
                'error' => $error->getMessage()
            ], 404);
        }
    }
}
