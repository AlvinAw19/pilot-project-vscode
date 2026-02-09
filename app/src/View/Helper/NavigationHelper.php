<?php
declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Entity\User;
use Authentication\IdentityInterface;
use Cake\View\Helper;

/**
 * Navigation Helper
 *
 * Provides role-based navigation links for the application.
 * Determines links based on user role, generates navigation URLs using routing,
 * and highlights the active menu item based on current controller/prefix.
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class NavigationHelper extends Helper
{
    /**
     * Helpers used by this helper
     *
     * @var array<string>
     */
    protected $helpers = ['Html'];

    /**
     * Get navigation links based on user role.
     *
     * @param \Authentication\IdentityInterface|null $identity The current user identity.
     * @return array<int, array{label: string, url: array<string, mixed>|string, active: bool}>
     */
    public function getLinks(?IdentityInterface $identity): array
    {
        if ($identity === null) {
            return $this->getGuestLinks();
        }

        /** @var \App\Model\Entity\User $user */
        $user = $identity->getOriginalData();
        $role = $user->role;

        switch ($role) {
            case User::ROLE_ADMIN:
                return $this->getAdminLinks();
            case User::ROLE_SELLER:
                return $this->getSellerLinks();
            case User::ROLE_BUYER:
            default:
                return $this->getBuyerLinks();
        }
    }

    /**
     * Get navigation links for guests (unauthenticated users).
     *
     * @return array<int, array{label: string, url: array<string, mixed>|string, active: bool}>
     */
    protected function getGuestLinks(): array
    {
        return [
            [
                'label' => __('Log In'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'login'],
                'active' => $this->isActive('Users', 'login'),
            ],
            [
                'label' => __('Sign Up'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'register'],
                'active' => $this->isActive('Users', 'register'),
            ],
        ];
    }

    /**
     * Get navigation links for buyers.
     *
     * @return array<int, array{label: string, url: array<string, mixed>|string, active: bool}>
     */
    protected function getBuyerLinks(): array
    {
        return [
            [
                'label' => __('My Profile'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'profile'],
                'active' => $this->isActive('Users', 'profile'),
            ],
            [
                'label' => __('Log Out'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'logout'],
                'active' => false,
            ],
        ];
    }

    /**
     * Get navigation links for sellers.
     *
     * @return array<int, array{label: string, url: array<string, mixed>|string, active: bool}>
     */
    protected function getSellerLinks(): array
    {
        return [
            [
                'label' => __('Dashboard'),
                'url' => ['prefix' => 'Seller', 'controller' => 'Reports', 'action' => 'index'],
                'active' => $this->isActive('Reports', null, 'Seller'),
            ],
            [
                'label' => __('My Products'),
                'url' => ['prefix' => 'Seller', 'controller' => 'Products', 'action' => 'index'],
                'active' => $this->isActive('Products', null, 'Seller'),
            ],
            [
                'label' => __('My Orders'),
                'url' => ['prefix' => 'Seller', 'controller' => 'Orders', 'action' => 'index'],
                'active' => $this->isActive('Orders', null, 'Seller'),
            ],
            [
                'label' => __('My Profile'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'profile'],
                'active' => $this->isActive('Users', 'profile'),
            ],
            [
                'label' => __('Log Out'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'logout'],
                'active' => false,
            ],
        ];
    }

    /**
     * Get navigation links for admins.
     *
     * @return array<int, array{label: string, url: array<string, mixed>|string, active: bool}>
     */
    protected function getAdminLinks(): array
    {
        return [
            [
                'label' => __('Dashboard'),
                'url' => ['prefix' => 'Admin', 'controller' => 'Reports', 'action' => 'index'],
                'active' => $this->isActive('Reports', null, 'Admin'),
            ],
            [
                'label' => __('My Users'),
                'url' => ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index'],
                'active' => $this->isActive('Users', null, 'Admin'),
            ],
            [
                'label' => __('My Categories'),
                'url' => ['prefix' => 'Admin', 'controller' => 'Categories', 'action' => 'index'],
                'active' => $this->isActive('Categories', null, 'Admin'),
            ],
            [
                'label' => __('My Products'),
                'url' => ['prefix' => 'Admin', 'controller' => 'Products', 'action' => 'index'],
                'active' => $this->isActive('Products', null, 'Admin'),
            ],
            [
                'label' => __('My Orders'),
                'url' => ['prefix' => 'Admin', 'controller' => 'Orders', 'action' => 'index'],
                'active' => $this->isActive('Orders', null, 'Admin'),
            ],
            [
                'label' => __('Logs'),
                'url' => ['prefix' => 'Admin', 'controller' => 'Logs', 'action' => 'index'],
                'active' => $this->isActive('Logs', null, 'Admin'),
            ],
            [
                'label' => __('My Profile'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'profile'],
                'active' => $this->isActive('Users', 'profile'),
            ],
            [
                'label' => __('Log Out'),
                'url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'logout'],
                'active' => false,
            ],
        ];
    }

    /**
     * Check if a menu item is active based on current controller, action, and prefix.
     *
     * @param string $controller The controller name to check.
     * @param string|null $action The action name to check (optional).
     * @param string|null $prefix The prefix to check (optional).
     * @return bool
     */
    protected function isActive(string $controller, ?string $action = null, ?string $prefix = null): bool
    {
        $request = $this->getView()->getRequest();
        $currentController = $request->getParam('controller');
        $currentAction = $request->getParam('action');
        $currentPrefix = $request->getParam('prefix');

        // Check controller match
        if ($currentController !== $controller) {
            return false;
        }

        // Check prefix match if specified
        if ($prefix !== null && $currentPrefix !== $prefix) {
            return false;
        }

        // Check action match if specified
        if ($action !== null && $currentAction !== $action) {
            return false;
        }

        return true;
    }

    /**
     * Render navigation links as HTML.
     *
     * @param \Authentication\IdentityInterface|null $identity The current user identity.
     * @return string
     */
    public function render($identity): string
    {
        $links = $this->getLinks($identity);
        $html = '';

        foreach ($links as $link) {
            $class = $link['active'] ? 'nav-link active' : 'nav-link';
            $html .= $this->Html->link(
                $link['label'],
                $link['url'],
                ['class' => $class]
            );
        }

        return $html;
    }
}
