<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 * fields = {"email"},
 * message ="Email déja utilisé !"
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="name is required")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="fname is required")
     */
    private $fname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="birthday is required")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="gender is required")
     */
    private $gender;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="num is required")
     * @Assert\Length(min="8",minMessage="Votre num de télèphone doit contenir 8 entiers")
     * @Assert\Length(max="8",maxMessage="Votre num de télèphone doit contenir 8 entiers")
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="password is required")
     * @Assert\Length(min="6",minMessage="Votre mot de passe doit contenir au min 6 caractères")
     */
    private $password;

    /**
     *@Assert\NotBlank(message="confirmpassword is required")
     *@Assert\EqualTo(propertyPath="password",message="Vérifier votre password")
     */

    private $confirmpassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getconfirmpassword(): ?string
    {
        return $this->confirmpassword;
    }

    public function setconfirmpassword(string $confirmpassword): self
    {
        $this->confirmpassword = $confirmpassword;

        return $this;
    }
}
