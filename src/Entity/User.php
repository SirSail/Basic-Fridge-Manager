<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "User")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    
    private array $roles = [];

    /**
     * Relacja OneToMany z tabelą UserRole
     * Jeden użytkownik może mieć wiele ról przypisanych poprzez tabelę UserRole
     */
    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: UserRole::class, cascade: ['persist', 'remove'])]
    private Collection $userRoles;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string 
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getSalt(): ?string
    {
        return null; // Zazwyczaj można to zostawić jako null, np. przy bcrypt lub argon2i
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Pobiera role z pola JSON, jak i z powiązanych encji Role przez UserRole
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Domyślna rola, jeśli pole jest puste
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        // Pobierz role z powiązanych encji Role poprzez UserRole
        foreach ($this->userRoles as $userRole) {
            $role = $userRole->getRoleId()->getRoleName();
            if (!in_array($role, $roles)) {
                $roles[] = $role;
            }
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        
        $this->roles = $roles;
        
        return $this;
    }

    /**
     * Metody do zarządzania relacją z UserRole
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): static
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->setUserId($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): static
    {
        if ($this->userRoles->removeElement($userRole)) {
            // usunięcie relacji UserRole
            if ($userRole->getUserId() === $this) {
                $userRole->setUserId(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // np. usuń plainPassword, jeśli tymczasowo je przechowujesz
    }
}