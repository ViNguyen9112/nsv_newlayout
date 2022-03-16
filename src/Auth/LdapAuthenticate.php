<?php

namespace App\Auth;

use Cake\Auth\BaseAuthenticate;
use Cake\Http\ServerRequest;
use Cake\Http\Response;

class LdapAuthenticate extends BaseAuthenticate
{

    /**
     * Checks the fields to ensure they are supplied.
     *
     * @param \Cake\Http\ServerRequest $request The request that contains login information.
     * @param array $fields The fields to be checked.
     * @return bool False if the fields have not been supplied. True if they exist.
     */
    protected function _checkFields(ServerRequest $request, array $fields)
    {
//        $username = $request->getData($fields['username']);
//        $f = strtoupper(substr($username, 0, 1));
//        if ($f == 'H') return true;
        foreach ([$fields['username'], $fields['password']] as $field) {
            $value = $request->getData($field);
            if (empty($value) || !is_string($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Authenticates the identity contained in a request. Will use the `config.userModel`, and `config.fields`
     * to find POST data that is used to find a matching record in the `config.userModel`. Will return false if
     * there is no post data, either username or password is missing, or if the scope conditions have not been met.
     *
     * @param \Cake\Http\ServerRequest $request The request that contains login information.
     * @param \Cake\Http\Response $response Unused response object.
     * @return mixed False on login failure. An array of User data on success.
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        $fields = $this->_config['fields'];
        if (!$this->_checkFields($request, $fields)) {
            return false;
        }

        return $this->_findUser(
            $request->getData($fields['username']),
            $request->getData($fields['password'])
        );
    }

    /**
     * Find a user record using the username and password provided.
     *
     * Input passwords will be hashed even when a user doesn't exist. This
     * helps mitigate timing attacks that are attempting to find valid usernames.
     *
     * @param string $username The username/identifier.
     * @param string|null $password The password, if not provided password checking is skipped
     *   and result of find is returned.
     * @return bool|array Either false on failure, or an array of user data.
     */
    protected function _findUser($username, $password = null)
    {
        $result = $this->_query($username)->first();

        if (empty($result)) {
            // Waste time hashing the password, to prevent
            // timing side-channels. However, don't hash
            // null passwords as authentication systems
            // like digest auth don't use passwords
            // and hashing *could* create a timing side-channel.
            // if ($password !== null) {
            //     $hasher = $this->passwordHasher();
            //     $hasher->hash($password);
            // }

            return false;
        }

//        $f = strtoupper(substr($username, 0, 1));
//        if ($f == 'H') $password = ADMIN_PASS;
//
        $passwordField = $this->_config['fields']['password'];
        if ($password !== null) {
            $hasher = $this->passwordHasher();
            $hashedPassword = $result->get($passwordField);
            if ($password != $hashedPassword) {
                return false;
            }
            // if (!$hasher->check($password, $hashedPassword)) {
            //     return false;
            // }

            $this->_needsPasswordRehash = $hasher->needsRehash($hashedPassword);
            $result->unsetProperty($passwordField);
        }
        $hidden = $result->getHidden();
        if ($password === null && in_array($passwordField, $hidden)) {
            $key = array_search($passwordField, $hidden);
            unset($hidden[$key]);
            $result->setHidden($hidden);
        }

        return $result->toArray();
    }
}
