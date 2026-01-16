<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\ForgotPasswordForm;
use App\Form\ResetPasswordForm;
use App\Mailer\UserMailer;
use App\Model\Entity\User;
use Cake\Event\EventInterface;

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
        $this->Authentication->addUnauthenticatedActions(['login', 'register', 'forgotPassword', 'resetPassword']);
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
     * Forgot Password method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function forgotPassword()
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
                    $this->Flash->success(__('If the email exists, a reset link has been sent.'));
                } else {
                    $this->Flash->success(__('If the email exists, a reset link has been sent.'));
                }
                return $this->redirect(['action' => 'login']);
            } else {
                $this->Flash->error(__('Please enter a valid email.'));
            }
        }
        $this->set('form', $form);
    }

    /**
     * Reset Password method
     *
     * @param string|null $token Reset token
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function resetPassword(?string $token = null)
    {
        $this->Authorization->skipAuthorization();
        if (!$token) {
            throw new \Cake\Http\Exception\NotFoundException();
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
}
