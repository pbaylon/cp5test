<?php
    declare(strict_types=1);

    namespace App\Controller\Api;

    use App\Controller\AppController;
    use Cake\Http\Exception\BadRequestException;
    use Cake\Http\Exception\UnauthorizedException;
    use Cake\Utility\Security;
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
            $data = $this->request->getData();

            $username = trim(strtolower($data['username'] ?? ''));
            $password = trim($data['password'] ?? '');
            $email    = trim($data['email'] ?? '');
            $fname    = trim($data['fname'] ?? '');
            $lname    = trim($data['lname'] ?? '');
            $role     = isset($data['role']) ? (int)$data['role'] : null;
            $has_pic  = isset($data['has_pic']) ? (int)$data['has_pic'] : 0;

            if (empty($username) || empty($password)) {
                throw new BadRequestException('Username and password are required.');
            }

            // Prevent duplicate usernames & emails
            if ($this->Users->exists(['username' => $username])) {
                throw new BadRequestException('Username already exists.');
            }
            if (!empty($email) && $this->Users->exists(['email' => $email])) {
                throw new BadRequestException('Email already exists.');
            }

            $user = $this->Users->patchEntity($this->Users->newEmptyEntity(), [
                'username'   => $username,
                'password'   => $password,
                'email'      => $email,
                'fname'      => $fname,
                'lname'      => $lname,
                'role'       => $role,
                'has_pic'    => $has_pic,
                'is_active'  => true,
            ]);

            if (!$this->Users->save($user)) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => false,
                        'errors' => $user->getErrors()
                    ]));
            }

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'status'  => true,
                    'message' => 'User registered successfully.',
                    'user_id' => $user->id
                ]));
        }


        //Login user
       public function login()
        {
            $username = trim(strtolower($this->request->getData('username')));
            $password = trim($this->request->getData('password'));

            if (empty($username) || empty($password)) {
                throw new BadRequestException('Username and password are required');
            }

            $user = $this->Users->find()
                ->where(['username' => $username])
                ->first();

            if (!$user || !password_verify($password, $user->password)) {
                throw new UnauthorizedException('Invalid credentials');
            }

            // If user is inactive, reactivate on successful login
            if (!$user->is_active) {
                $user->is_active = true;
                $this->Users->save($user);
            }

            $plainToken = bin2hex(Security::randomBytes(32));
            $hashedToken = hash('sha256', $plainToken);

            $tokenEntity = $this->Tokens->newEntity([
                'user_id'    => $user->id,
                'token'      => $hashedToken,
                // 'ip_address' => $this->request->clientIp(),
                'expires_at' => (new \DateTimeImmutable())->modify('+2 days')
            ]);

            $this->Tokens->saveOrFail($tokenEntity);

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => true,
                    'token'  => $plainToken
                ]));
        }


        //Logout User
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

            $tokenPlain = $matches[1];
            $tokenHash = hash('sha256', $tokenPlain);

            // Deactivate the user
            $user->is_active = false;
            $this->Users->save($user);

            // Delete the token
            $this->Tokens->deleteAll([
                'user_id' => $user->id,
                'token'   => $tokenHash
            ]);

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status'  => true,
                    'message' => 'Logged out successfully and user deactivated.'
                ]));
        }


        //User profile
        public function profile()
        {
            $user = $this->request->getAttribute('user');
            if (!$user) {
                throw new UnauthorizedException('Not authenticated');
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => true,
                    'data' => [
                        'id'        => $user->id,
                        'username'  => $user->username,
                        'email'     => $user->email ?? null,
                        'is_active' => $user->is_active,
                    ]
                ]));
        }   
    }
