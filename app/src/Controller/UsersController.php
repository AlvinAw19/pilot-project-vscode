<?php
declare(strict_types=1);

namespace App\Controller;

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
        $this->Authorization->skipAuthorization();
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
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $identity = $this->Authentication->getIdentity();
            $redirect = $this->Authentication->getLoginRedirect();

            // Always redirect based on role, ignore stored redirect for better UX
            if ($identity->get('role') === 'admin') {
                return $this->redirect(['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index']);
            }
            elseif ($identity->get('role') === 'seller') {
                return $this->redirect(['prefix' => 'Seller', 'controller' => 'Products', 'action' => 'index']);
            }
            return $this->redirect(['controller' => 'Pages', 'action' => 'display']);
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
     * Clears the user's session using the Authentication plugin's logout mechanism
     * and redirects to the login page. This action is accessible to all users.
     *
     * @return \Cake\Http\Response Redirects to the login page.
     */
    public function logout()
    {
        $this->Authorization->skipAuthorization();
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}
