<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use League\OAuth2\Client\Provider\Google;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class UsersController extends AppController
{
    /**
     * beforeFilter callback.
     *
     * Allows the login action to be accessed without requiring
     * authentication. This prevents the Authentication middleware
     * from redirecting unauthenticated users back to the login page,
     * which would otherwise result in an infinite redirect loop.
     *
     * @param \Cake\Event\EventInterface<\App\Controller\UsersController> $event The event object.
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login', 'register', 'googleLogin', 'googleCallback', 'googleSignup']);
    }

    /**
     * Register method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful register, renders view otherwise.
     */
    public function register()
    {
        $this->Authorization->skipAuthorization();
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->role = 'buyer';
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Handles user login.
     *
     * @return \Cake\Http\Response|null Redirects on successful login,
     *   otherwise renders the login page.
     */
    public function login()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $identity = $this->request->getAttribute('identity');

            if ($identity) {
                switch ($identity->get('role')) {
                    case User::ROLE_ADMIN:
                        return $this->redirect(['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index']);

                    case User::ROLE_SELLER:
                        return $this->redirect(['prefix' => 'Seller', 'controller' => 'Products', 'action' => 'index']);

                    case User::ROLE_BUYER:
                        return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                }
            }

            // Guest fallback
            return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && ($result === null || !$result->isValid())) {
            $this->Flash->error(__('Invalid username or password'));
        }

        return null;
    }

    /**
     * Logs the user out of the application.
     *
     * If the current authentication result is valid, the user's session
     * is cleared using the Authentication plugin's logout mechanism.
     * After logging out, the user is redirected to the login page.
     *
     * @return \Cake\Http\Response|null Redirects to the login page on success,
     *   or null if no authenticated user was found.
     */
    public function logout()
    {
        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    /**
     * Initiates Google OAuth login.
     *
     * @return \Cake\Http\Response|null Redirects to Google OAuth consent screen.
     */
    public function googleLogin()
    {
        $this->Authorization->skipAuthorization();
        $provider = new Google([
            'clientId' => Configure::read('GoogleOAuth.clientId'),
            'clientSecret' => Configure::read('GoogleOAuth.clientSecret'),
            'redirectUri' => Configure::read('GoogleOAuth.redirectUri'),
        ]);

        $authUrl = $provider->getAuthorizationUrl(['prompt' => 'select_account']);
        $this->request->getSession()->write('oauth2state', $provider->getState());
        $this->request->getSession()->write('oauth_mode', 'login'); // Flag for login

        return $this->redirect($authUrl);
    }

    /**
     * Initiates Google OAuth signup.
     *
     * @return \Cake\Http\Response|null Redirects to Google OAuth consent screen.
     */
    public function googleSignup()
    {
        $this->Authorization->skipAuthorization();
        $provider = new Google([
            'clientId' => Configure::read('GoogleOAuth.clientId'),
            'clientSecret' => Configure::read('GoogleOAuth.clientSecret'),
            'redirectUri' => Configure::read('GoogleOAuth.redirectUri'),
        ]);

        $authUrl = $provider->getAuthorizationUrl(['prompt' => 'select_account']);
        $this->request->getSession()->write('oauth2state', $provider->getState());
        $this->request->getSession()->write('oauth_mode', 'signup'); // Flag for signup

        return $this->redirect($authUrl);
    }

    /**
     * Handles Google OAuth callback.
     *
     * @return \Cake\Http\Response|null Redirects based on login result.
     */
    public function googleCallback()
    {
        $this->Authorization->skipAuthorization();
        if ($this->request->getQuery('error')) {
            $this->Flash->error(__('Access denied'));
            return $this->redirect(['action' => 'login']);
        }

        $provider = new Google([
            'clientId' => Configure::read('GoogleOAuth.clientId'),
            'clientSecret' => Configure::read('GoogleOAuth.clientSecret'),
            'redirectUri' => Configure::read('GoogleOAuth.redirectUri'),
        ]);

        $session = $this->request->getSession();
        $expectedState = $session->read('oauth2state');
        $providedState = $this->request->getQuery('state');

        if (empty($providedState) || ($providedState !== $expectedState)) {
            $session->delete('oauth2state');
            $this->Flash->error(__('Invalid state'));
            return $this->redirect(['action' => 'login']);
        }

        try {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $this->request->getQuery('code')
            ]);

            $user = $provider->getResourceOwner($token);

            $mode = $session->read('oauth_mode');
            $session->delete('oauth_mode'); // Clean up

            // Find user by google_id
            $existingUser = $this->Users->find()->where(['google_id' => $user->getId()])->first();

            if ($mode === 'login') {
                if ($existingUser) {
                    $this->Authentication->setIdentity($existingUser);
                    return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                } else {
                    // Auto sign up for login
                    $existingByEmail = $this->Users->find()->where(['email' => $user->getEmail()])->first();
                    if ($existingByEmail) {
                        if ($existingByEmail->google_id) {
                            // Already linked, login
                            $this->Authentication->setIdentity($existingByEmail);
                            return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                        } else {
                            // Email exists but not linked to Google, link it
                            $existingByEmail->google_id = $user->getId();
                            $existingByEmail->provider = 'google';
                            if ($this->Users->save($existingByEmail)) {
                                $this->Authentication->setIdentity($existingByEmail);
                                return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                            } else {
                                $this->Flash->error(__('Unable to link Google account. Please contact support.'));
                                return $this->redirect(['action' => 'login']);
                            }
                        }
                    } else {
                        // Create new user
                        $newUser = $this->Users->newEntity([
                            'name' => $user->getName(),
                            'email' => $user->getEmail(),
                            'google_id' => $user->getId(),
                            'provider' => 'google',
                            'role' => 'buyer',
                            'address' => 'Not provided',
                            'password' => bin2hex(random_bytes(16)), // Random password for OAuth users
                        ]);

                        if ($this->Users->save($newUser)) {
                            $this->Authentication->setIdentity($newUser);
                            return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                        } else {
                            $errors = $newUser->getErrors();
                            $errorMessages = [];
                            foreach ($errors as $field => $fieldErrors) {
                                $errorMessages[] = $field . ': ' . implode(', ', $fieldErrors);
                            }
                            $this->Flash->error(__('Unable to create user: ') . implode('; ', $errorMessages));
                            return $this->redirect(['action' => 'login']);
                        }
                    }
                }
            } elseif ($mode === 'signup') {
                if ($existingUser) {
                    $this->Flash->error(__('An account with this Google account already exists. Please login instead.'));
                    return $this->redirect(['action' => 'login']);
                } else {
                    // Check if email already exists
                    $existingByEmail = $this->Users->find()->where(['email' => $user->getEmail()])->first();
                    if ($existingByEmail) {
                        if ($existingByEmail->google_id) {
                            // Already linked, login
                            $this->Authentication->setIdentity($existingByEmail);
                            return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                        } else {
                            // Email exists but not linked to Google, link it
                            $existingByEmail->google_id = $user->getId();
                            $existingByEmail->provider = 'google';
                            if ($this->Users->save($existingByEmail)) {
                                $this->Authentication->setIdentity($existingByEmail);
                                return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                            } else {
                                $this->Flash->error(__('Unable to link Google account. Please contact support.'));
                                return $this->redirect(['action' => 'login']);
                            }
                        }
                    } else {
                        // Create new user
                        $newUser = $this->Users->newEntity([
                            'name' => $user->getName(),
                            'email' => $user->getEmail(),
                            'google_id' => $user->getId(),
                            'provider' => 'google',
                            'role' => 'buyer',
                            'address' => 'Not provided',
                            'password' => bin2hex(random_bytes(16)), // Random password for OAuth users
                        ]);

                        if ($this->Users->save($newUser)) {
                            $this->Authentication->setIdentity($newUser);
                            return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
                        } else {
                            $errors = $newUser->getErrors();
                            $errorMessages = [];
                            foreach ($errors as $field => $fieldErrors) {
                                $errorMessages[] = $field . ': ' . implode(', ', $fieldErrors);
                            }
                            $this->Flash->error(__('Unable to create user: ') . implode('; ', $errorMessages));
                            return $this->redirect(['action' => 'login']);
                        }
                    }
                }
            } else {
                $this->Flash->error(__('Invalid OAuth mode.'));
                return $this->redirect(['action' => 'login']);
            }
        } catch (\Exception $e) {
            $this->Flash->error(__('OAuth error: ') . $e->getMessage());
            return $this->redirect(['action' => 'login']);
        }
    }
}
