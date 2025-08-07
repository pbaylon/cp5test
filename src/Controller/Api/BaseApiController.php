<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

class BaseApiController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->request->allowMethod(['get', 'post', 'put', 'delete']);
        $this->response = $this->response->withType('application/json');
    }

    protected function json(array $data = [], int $status = 200): \Cake\Http\Response
    {
        return $this->response
            ->withStatus($status)
            ->withStringBody(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
