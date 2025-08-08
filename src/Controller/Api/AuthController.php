<?php
    declare(strict_types=1);

    namespace App\Controller\Api;

    use App\Controller\AppController;
    use Cake\Http\Exception\BadRequestException;
    use Cake\Http\Exception\UnauthorizedException;
    use App\Model\Table\UsersTable;
    use App\Model\Table\PersonalTokensTable;
    
    class AuthController extends AppController
    {
        protected UsersTable $Users;
        protected PersonalTokensTable $Tokens;

        public function initialize(): void 
        {
            parent::initialize();
        
            $this->Users = $this->fetchTable('Users');
            $this->Tokens = $this->fetchTable('PersonalTokens');
            // $this->Users = (new TableLocator())->get('Users');
            // $this->Tokens = (new TableLocator())->get('Tokens');
        }
        //Register User
        public function register()
        {
            $result = $this->Users->registerUser($this->request->getData());

            if (!$result['status']) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => false,
                        'message' => $result['message'] ?? 'Validation failed',
                        'errors' => $result['errors'] ?? null
                    ]));
            }

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => true,
                    'message' => 'User registered successfully.',
                    'user_id' => $result['user']->id
                ]));
        }

        public function login()
        {
            $username = trim(strtolower($this->request->getData('username')));
            $password = trim($this->request->getData('password'));

            if (empty($username) || empty($password)) {
                throw new BadRequestException('Username and password are required');
            }

            $user = $this->Users->authenticateUser($username, $password);

            if (!$user) {
                throw new UnauthorizedException('Invalid credentials or user inactive');
            }

            $token = $this->Tokens->generateTokenForUser($user);

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => true,
                    'token' => $token
                ]));
        }

        public function logout()
        {
            $user = $this->request->getAttribute('user');
            if (!$user) {
                throw new UnauthorizedException('Not authenticated');
            }

            $header = $this->request->getHeaderLine('Authorization');
            if (!preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
                throw new UnauthorizedException('Missing or invalid token');
            }

            $token = $matches[1];
            $this->Users->logoutUser($user, $token);

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => true,
                    'message' => 'Logged out successfully.'
                ]));
        }

        public function profile()
        {
            $user = $this->request->getAttribute('user');
            if (!$user) {
                throw new UnauthorizedException('Not authenticated');
            }

            $profile = $this->Users->getUserProfile($user->id);

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => true,
                    'data' => $profile
                ]));
        }

    }
