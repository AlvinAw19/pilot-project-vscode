<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\ForgotPasswordForm;
use App\Form\ResetPasswordForm;
use App\Mailer\UserMailer;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Exception;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;

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
        $this->Authentication->addUnauthenticatedActions(['login', 'register',
            'forgotPassword', 'resetPassword', 'googleLogin', 'googleCallback']);
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
                        return $this->redirect(['prefix' => 'Admin', 'controller' => 'Reports', 'action' => 'index']);

                    case User::ROLE_SELLER:
                        return $this->redirect(['prefix' => 'Seller', 'controller' => 'Reports', 'action' => 'index']);

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
     * Forgot Password method
     *
     * @return \Cake\Http\Response|null Renders view
     */
    public function forgotPassword(): ?Response
    {
        $this->Authorization->skipAuthorization();
        $form = new ForgotPasswordForm();
        if ($this->request->is('post')) {
            if ($form->execute($this->request->getData())) {
                $email = $this->request->getData('email');
                $token = $this->Users->generateResetToken($email);
                if ($token) {
                    $mailer = new UserMailer();
                    $mailer->passwordResetEmail($email, $token);
                    $this->Flash->success(__('A reset link has been sent to your email.'));
                } else {
                    $this->Flash->error(__('No account found with that email address. Please check and try again.'));
                }

                return $this->redirect(['action' => 'login']);
            } else {
                $this->Flash->error(__('Please enter a valid email.'));
            }
        }
        $this->set(compact('form'));

        return null;
    }

    /**
     * Reset Password method
     *
     * @param string|null $token Reset token
     * @return \Cake\Http\Response|null Renders view
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function resetPassword(?string $token = null): ?Response
    {
        $this->Authorization->skipAuthorization();
        if (!$token) {
            throw new NotFoundException();
        }

        $form = new ResetPasswordForm();
        if ($this->request->is('post')) {
            if ($form->execute($this->request->getData())) {
                $newPassword = $this->request->getData('password');
                if ($this->Users->resetPassword($token, $newPassword)) {
                    $this->Flash->success(__('Password has been reset. Please log in.'));

                    return $this->redirect(['action' => 'login']);
                } else {
                    $this->Flash->error(__('Invalid or expired token.'));
                }
            } else {
                $this->Flash->error(__('Please correct the errors below.'));
            }
        }
        $this->set(compact('form', 'token'));

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
        $this->Authorization->skipAuthorization();
        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    /**
     * Initiates Google OAuth.
     *
     * @return \Cake\Http\Response|null Redirects to Google OAuth consent screen.
     */
    public function googleLogin(): ?Response
    {
        $this->Authorization->skipAuthorization();

        // Create Google OAuth provider instance with config from app_local.php
        $provider = new Google([
            'clientId' => Configure::read('GoogleOAuth.clientId'),
            'clientSecret' => Configure::read('GoogleOAuth.clientSecret'),
            'redirectUri' => Configure::read('GoogleOAuth.redirectUri'),
        ]);

        // Generate consent URL for user to select account then redirect user to Google OAuth page
        $authUrl = $provider->getAuthorizationUrl(['prompt' => 'select_account']);
        $this->request->getSession()->write('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    /**
     * Handles Google OAuth callback.
     *
     * @return \Cake\Http\Response|null Redirects based on login result.
     */
    public function googleCallback(): ?Response
    {
        $this->Authorization->skipAuthorization();

        // Check for OAuth errors and redirect to login page
        if ($this->request->getQuery('error')) {
            $this->Flash->error(__('Access denied'));

            return $this->redirect(['action' => 'login']);
        }

        // Create new http request with same config from app_local
        $provider = new Google([
            'clientId' => Configure::read('GoogleOAuth.clientId'),
            'clientSecret' => Configure::read('GoogleOAuth.clientSecret'),
            'redirectUri' => Configure::read('GoogleOAuth.redirectUri'),
        ]);

        $session = $this->request->getSession();
        $expectedState = $session->read('oauth2state');
        $providedState = $this->request->getQuery('state');

        // State validation
        if (empty($providedState) || ($providedState !== $expectedState)) {
            $session->delete('oauth2state');
            $this->Flash->error(__('Invalid state'));

            return $this->redirect(['action' => 'login']);
        }

        try {
            // Exchange authorization code for access token
            /** @var \League\OAuth2\Client\Token\AccessToken $token */
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $this->request->getQuery('code'),
            ]);

            // Get Google User's Profile
            /** @var \League\OAuth2\Client\Provider\GoogleUser $googleUser */
            $googleUser = $provider->getResourceOwner($token);

            // Call findOrCreateOAuthUser() to create/link user account
            $user = $this->findOrCreateOAuthUser($googleUser);

            if ($user) {
                $this->Authentication->setIdentity($user);

                return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to authenticate with Google.'));

                return $this->redirect(['action' => 'login']);
            }
        } catch (Exception $e) {
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
    private function findOrCreateOAuthUser(GoogleUser $googleUser): ?User
    {
        // Case 1: User signup using google. Find user by google_id
        /** @var \App\Model\Entity\User|null $existingUser */
        $existingUser = $this->Users->find()->where(['google_id' => $googleUser->getId()])->first();

        if ($existingUser) {
            return $existingUser;
        }

        // Case 2: if user already signup locally and want to login using google. Find user by email
        /** @var \App\Model\Entity\User|null $existingByEmail */
        $existingByEmail = $this->Users->find()->where(['email' => $googleUser->getEmail()])->first();

        if ($existingByEmail) {
            // Link existing account to google
            $existingByEmail->google_id = $googleUser->getId();
            $existingByEmail->provider = 'google';
            if ($this->Users->save($existingByEmail)) {
                return $existingByEmail;
            } else {
                $this->Flash->error(__('Unable to link Google account. Please contact support.'));

                return null;
            }
        } else {
            // Create new user
            /** @var \App\Model\Entity\User $newUser */
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
     * View and edit current user profile.
     *
     * @return \Cake\Http\Response|null Renders view.
     */
    public function profile(): ?Response
    {
        $identity = $this->request->getAttribute('identity');
        $userId = $identity->getIdentifier();

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->get($userId);

        $this->Authorization->authorize($user);

        // Determine if user has a password set (for OAuth users who may not have one)
        $hasPassword = $user->password !== null;

        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();
            $hasError = false;

            // Handle password change/set
            if (!empty($data['new_password'])) {
                if ($data['new_password'] !== ($data['confirm_password'] ?? '')) {
                    $this->Flash->error(__('Passwords do not match.'));
                    $hasError = true;
                } else {
                    $data['password'] = $data['new_password'];
                }
            }

            if (!$hasError) {
                $user = $this->Users->patchEntity($user, $data, [
                    'accessibleFields' => [
                        'name' => true,
                        'address' => true,
                        'password' => true,
                        'theme' => true,
                    ],
                ]);

                if ($this->Users->save($user)) {
                    // Refresh the session identity with updated user data
                    $this->Authentication->setIdentity($user);

                    $this->Flash->success(__('Your profile has been updated.'));

                    return $this->redirect(['action' => 'profile']);
                }
                $this->Flash->error(__('Your profile could not be updated. Please, try again.'));
            }
        }

        $this->set(compact('user', 'hasPassword'));

        return null;
    }
}
