<?php

namespace Harentius\BlogBundle\Entity;

use Harentius\BlogBundle\Entity\Base\IdentifiableEntityTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\AdminUserRepository")
 */
class AdminUser implements UserInterface
{
    use IdentifiableEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max=50)
     * @SymfonyConstraints\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max=255)
     * @SymfonyConstraints\Type("string")

     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max=255)
     * @SymfonyConstraints\Type("string")
     */
    private $salt;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     *
     */
    public function __construct()
    {
        $this->salt = md5(uniqid(null, true));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSalt($value)
    {
        $this->salt = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPlainPassword($value)
    {
        $this->plainPassword = $value;
        $this->password = hash('sha512', $value . $this->salt);

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}
