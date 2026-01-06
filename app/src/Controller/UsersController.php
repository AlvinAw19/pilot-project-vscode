<?php
declare(strict_types=1);

namespace App\Controller;

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
        $this->Authentication->addUnauthenticatedActions(['login', 'register']);
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
            if ($identity && ($identity->get('role') === User::ROLE_ADMIN)) {
                return $this->redirect(['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index']);
            } elseif ($identity && ($identity->get('role') === User::ROLE_SELLER)) {
                return $this->redirect(['prefix' => 'Seller', 'controller' => 'Products', 'action' => 'index']);
            } elseif ($identity && ($identity->get('role') === User::ROLE_BUYER)) {
                return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
            }

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
}
