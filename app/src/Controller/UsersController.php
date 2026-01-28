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
        $this->Authentication->addUnauthenticatedActions(['login', 'register', 'googleLogin', 'googleCallback']);
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
            // Require password and address for non-OAuth registration
            if (empty($user->google_id)) {
                if (empty($user->password)) {
                    $user->setError('password', __('Password is required for registration.'));
                }
                if (empty($user->address)) {
                    $user->setError('address', __('Address is required for registration.'));
                }
            }
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
        // Check if user is trying to login with email/password but has no password (OAuth user)
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            if ($email) {
                $user = $this->Users->find()->where(['email' => $email])->first();
                if ($user && $user->password === null) {
                    $this->Flash->error(__('This account uses Google login. Please use the "Login with Google" button.'));
                    return null;
                }
            }
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
        $this->Authorization->skipAuthorization();

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    /**
     * Initiates Google OAuth.
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

            $googleUser = $provider->getResourceOwner($token);

            $user = $this->findOrCreateOAuthUser($googleUser);

            if ($user) {
                $this->Authentication->setIdentity($user);
                return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to authenticate with Google.'));
                return $this->redirect(['action' => 'login']);
            }
        } catch (\Exception $e) {
            $this->Flash->error(__('OAuth error: ') . $e->getMessage());
            return $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Finds an existing OAuth user or creates/links a new one.
     *
     * @param \League\OAuth2\Client\Provider\GoogleUser $googleUser The Google user object.
     * @return \App\Model\Entity\User|null The user entity or null on failure.
     */
    private function findOrCreateOAuthUser($googleUser)
    {
        // Find user by google_id
        $existingUser = $this->Users->find()->where(['google_id' => $googleUser->getId()])->first();

        if ($existingUser) {
            return $existingUser;
        }

        // Check if email exists
        $existingByEmail = $this->Users->find()->where(['email' => $googleUser->getEmail()])->first();

        if ($existingByEmail) {
            if ($existingByEmail->google_id) {
                return $existingByEmail;
            } else {
                // Link existing account
                $existingByEmail->google_id = $googleUser->getId();
                $existingByEmail->provider = 'google';
                if ($this->Users->save($existingByEmail)) {
                    return $existingByEmail;
                } else {
                    $this->Flash->error(__('Unable to link Google account. Please contact support.'));
                    return null;
                }
            }
        } else {
            // Create new user
            $newUser = $this->Users->newEntity([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'provider' => 'google',
                'role' => 'buyer',
                'address' => null,
                'password' => null,
            ]);

            if ($this->Users->save($newUser)) {
                return $newUser;
            } else {
                $errors = $newUser->getErrors();
                $errorMessages = [];
                foreach ($errors as $field => $fieldErrors) {
                    $errorMessages[] = $field . ': ' . implode(', ', $fieldErrors);
                }
                $this->Flash->error(__('Unable to create user: ') . implode('; ', $errorMessages));
                return null;
            }
        }
    }

    /**
     * Profile method - Placeholder for user profile.
     *
     * @return \Cake\Http\Response|null|void Renders view.
     */
    public function profile()
    {
        $this->Authorization->skipAuthorization();
    }
}
