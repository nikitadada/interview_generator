<?php

namespace App\AdminBundle\Security;

use App\AdminBundle\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    protected $dm;
    protected $class = User::class;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function loadUserByUsername($username)
    {
        $username = mb_strtolower($username);

        $user = $this->findUserBy([
            'email' => $username
        ]);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    public function loadUserById($id)
    {
        $user = $this->findUserBy([
            '_id' => $id
        ]);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('User "%s" does not exist.', $id));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        /** @var User $user */
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', $this->class, get_class($user)));
        }

        if (null === $reloadedUser = $this->findUserBy(['id' => $user->getId()])) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    protected function findUserBy($params)
    {
        return $this->dm->getRepository($this->class)->findOneBy($params);
    }

    public function supportsClass($class)
    {
        return $this->class === $class || is_subclass_of($class, $this->class);
    }
}
