<?php

namespace App\AdminBundle\Document;

use App\Util\StringGenerator;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Mongo\Document(collection="users", repositoryClass="App\AdminBundle\Document\Repository\UserRepository")
 */
class User implements UserInterface, EquatableInterface, \Serializable, AdvancedUserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    protected static $rolesList = [
        self::ROLE_ADMIN => 'Администратор',
    ];

    /**
     * @Mongo\Id(strategy="INCREMENT")
     */
    protected $id;

    /**
     * @Mongo\Field(type="string")
     */
    protected $name;

    /**
     * @Mongo\Field(type="string")
     */
    protected $email;

    /**
     * @Mongo\Field(type="string")
     */
    protected $salt;

    /**
     * @Mongo\Field(type="string")
     */
    protected $password;

    protected $plainPassword;

    /**
     * @Mongo\Field(type="collection")
     */
    protected $roles = [];

    /**
     * @Mongo\Field(type="date")
     */
    protected $createdAt;

    /**
     * @Mongo\Field(type="string")
     */
    protected $recoverToken;

    /**
     * @var bool
     * @Mongo\Field(type="bool")
     */
    protected $active = true;

    /**
     * @var bool
     * @Mongo\Field(type="bool")
     */
    protected $locked = false;


    public function __construct()
    {
        $this->salt = StringGenerator::generateRandom(36);
        $this->updateRecoverToken();
        $this->createdAt = new \DateTime();
    }

    public static function getRolesList()
    {
        return self::$rolesList;
    }

    public static function generateToken()
    {
        return substr(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36), 0, 10);
    }

    public function updateRecoverToken()
    {
        $this->recoverToken = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function isUser(User $user = null)
    {
        return null !== $user && $this->getId() === $user->getId();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $email = mb_strtolower($email);

        if ($email !== $this->email) {
            $this->email = $email;
        }

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        if ($plainPassword !== $this->plainPassword) {
            $this->plainPassword = $plainPassword;
        }

        return $this;
    }

    /**
     * @var UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        return $this->email === $user->getUsername() &&
            $this->password === $user->getPassword() &&
            $this->salt === $user->getSalt() &&
            serialize($this->getRoles()) === serialize($user->getRoles());
    }

    public function getRoles()
    {
        $roles = $this->roles;

        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    public function getRoleNames()
    {
        $names = [];

        foreach ($this->roles as $role) {
            if (isset(self::$rolesList[$role])) {
                $names[] = self::$rolesList[$role];
            }
        }

        return $names;
    }

    public function setRoles($roles)
    {
        $this->roles = array_values(array_unique($roles));

        return $this;
    }

    public function getRecoverToken()
    {
        return $this->recoverToken;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return void
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function __toString()
    {
        return (string)$this->email;
    }

    public function isSuperAdmin()
    {
        return in_array('ROLE_SUPER_ADMIN', $this->roles);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = !!$active;

        return $this;
    }

    public function getLocked()
    {
        return $this->locked;
    }

    public function setLocked($locked)
    {
        $this->locked = !!$locked;

        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->roles,
            $this->password,
            $this->salt,
            $this->active,
            $this->locked,
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        list (
            $this->id,
            $this->email,
            $this->roles,
            $this->password,
            $this->salt,
            $this->active,
            $this->locked
            ) = $data;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->active;
    }

}
