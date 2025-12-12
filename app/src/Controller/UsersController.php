<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
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
        // Configure the login and add user action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->allowUnauthenticated(['login', 'add']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->role = 'buyer';
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                // FIXME: redirect to product catalog when logged in in function 6

                return $this->redirect(['controller' => 'Pages',
                'action' => 'display',]);
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
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            // redirect to /display after login success
            // FIXME: redirect to individual page after set up of all role during function 6

            return $this->redirect(['controller' => 'Pages',
                'action' => 'display',]);
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
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        return null;
    }
}
