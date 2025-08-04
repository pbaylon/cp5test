<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Model\Table\InstallationsTable;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Installation Controller
 *
 */
class InstallationController extends AppController
{
    protected InstallationsTable $Installation;
    public function initialize(): void
    {
        parent::initialize();
        $this->Installation = $this->fetchTable('Installations');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->disableAutoRender();
        $query = $this->Installation->find()->where(['deleted_on IS' => null]);
        $installation = $this->paginate($query);

       return $this->jsonResponse([
        'status' => true,
        'data' => $installation->toArray(),
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
     * @param string|null $id Installation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->disableAutoRender();
        $installation = $this->Installation->get($id);

        if ($installation->deleted_on !== null) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Installation has been deleted.'
            ], 410);
        }

        return $this->jsonResponse([
            'status' => true,
            'data' => $installation
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
        $installation = $this->Installation->newEmptyEntity();

          if ($this->request->is('post')) {
            $data = $this->request->getData();

            $installation = $this->Installation->patchEntity($installation, $data);

            if ($this->Installation->save($installation)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The installation has been saved.',
                    'installation_id' => $installation->id
                ]);
            }

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to save installation.',
                'errors' => $installation->getErrors()
            ], 400);
        }

        return $this->jsonResponse([
            'status' => false,
            'message' => 'Method not allowed',
        ], 405);
    }

    /**
     * Edit method
     *
     * @param string|null $id Installation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
       $this->disableAutoRender();
        $installation = $this->Installation->get($id);

        if ($installation->deleted_on !== null) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Installation has been deleted.'
            ], 410);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $installation = $this->Installation->patchEntity($installation, $data);

            if ($this->Installation->save($installation)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The installation has been updated.',
                    'installation_id' => $installation->id
                ]);
            }

            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to update installation.',
                'errors' => $installation->getErrors()
            ], 400);
        }

        return $this->jsonResponse([
            'status' => false,
            'message' => 'Method not allowed',
        ], 405);
    }

    /**
     * Delete method
     *
     * @param string|null $id Installation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post', 'delete']);

        try{
            $installation = $this->Installation->get($id);
            if ($this->Installation->save($installation)) {
                return $this->jsonResponse([
                    'status' => true,
                    'message' => 'The installation has been deleted.',
                ]);
            }
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Failed to delete the installation.'
            ], 400);


        }catch (RecordNotFoundException $error) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Installation not found',
                'error' => $error->getMessage()
            ], 404);
        }
    }
}
