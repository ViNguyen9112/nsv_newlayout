<?php
namespace App\Auth;

use Cake\Http\Response;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component\CookieComponent;
use Cake\Http\ServerRequest;
use Cake\Event\Event;
use Cake\Network\Request;
//use Cake\Network\Response;

/**
 * An authentication adapter for AuthComponent
 *
 * Provides the ability to authenticate using COOKIE
 *
 * ```
 *    $this->Auth->config('authenticate', [
 *        'Authenticate.Cookie' => [
 *            'fields' => [
 *                'username' => 'username',
 *                'password' => 'password'
 *             ],
 *            'userModel' => 'Users',
 *            'scope' => ['Users.active' => 1],
 *            'crypt' => 'aes',
 *            'cookie' => [
 *                'name' => 'RememberMe',
 *                'time' => '+2 weeks',
 *            ]
 *        ]
 *    ]);
 * ```
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class CookieAdAuthenticate extends StaticAuthenticate
{
    /**
     * Constructor
     *
     * @param \Cake\Controller\ComponentRegistry $registry The Component registry
     *   used on this request.
     * @param array $config Array of config to use.
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        $this->_registry = $registry;
        $this->setConfig($config);
    }

    /**
     * Authenticate user
     *
     * @param Request $request Request object.
     * @param Response $response Response object.
     * @return array|bool Array of user info on success, false on falure.
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        return $this->getUser($request);
    }
    /**
     * Called from AuthComponent::logout()
     *
     * @param \Cake\Event\Event $event The dispatched Auth.logout event.
     * @param array $user User record.
     * @return void
     */
    public function logout(Event $event, array $user)
    {
        $this->_registry->Cookie->delete($this->_config['cookie']['name']);
    }
}
