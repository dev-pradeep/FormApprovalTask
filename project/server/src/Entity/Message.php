<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\MessageController;

/**
 * @ApiResource(
 *   itemOperations={
 *      "get"={"method"="GET", "path"="/admin/messages/{id}", "requirements"={"id"="\d+"}},
 *      "put"={"method"="PUT", "path"="/admin/messages/{id}", "requirements"={"id"="\d+"}},
 *      "delete"={"method"="DELETE", "path"="/admin/messages/{id}", "requirements"={"id"="\d+"}},
 *      "approveMessage"={
 *        "route_name"="approve_message",
 *        "swagger_context" = {
 *          "parameters" = {
 *            {
 *              "name" = "id",
 *              "in" = "path",
 *              "required" = "true",
 *              "type" = "integer"
 *            }
 *          },
 *          "responses" = {
 *            "200" = {
 *              "description" = "Returns true if the message is approved"
 *            }
 *          }
 *        }
 *      },
 *   },
 *   collectionOperations={
 *      "get"={"method"="GET", "path"="/messages"},
 *      "put"={"method"="POST", "path"="/messages"},
 *   }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\Table(name="messages")
 */
class Message
{
    const PENDING_STATUS  = 1;
    const APPROVED_STATUS = 2;
    const REJECTED_STATUS = 3;
    
    /* Delay in minutes */
    const REJECTION_DELAY = 5;    

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isApproved=false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status=self::PENDING_STATUS;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(?bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getStatus(): ?string
    {
        if ((int)$this->status==self::PENDING_STATUS) {
            return "Pending";
        } elseif ((int)$this->status==self::APPROVED_STATUS) {
            return "Approved";
        } else {
            return "Rejected";
        }
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}