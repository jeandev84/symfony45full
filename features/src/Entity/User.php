<?php
namespace App\Entity;

use App\Entity\Traits\HasTimestamp;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
*/
class User
{

    use HasTimestamp;



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
    */
    private $id;



    /**
     * @ORM\Column(type="string", length=255)
    */
    private $name;



    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="user", cascade={"remove"}, orphanRemoval=true)
    */
    private $videos;



    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
    */
    private $address;



    /**
     * user has followers
     *
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="following")
    */
    private $followed;



    /**
     * user following by others users
     *
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="followed")
    */
    private $following;




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


    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->followed = new ArrayCollection();
        $this->following = new ArrayCollection();
    }



    /**
     * @ORM\PrePersist
    */
    public function setCreatedAtValue()
    {
         $this->createdAt = new \DateTime();

         // dump($this->createdAt);
    }




    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setUser($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUser() === $this) {
                $video->setUser(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowed(): Collection
    {
        return $this->followed;
    }

    public function addFollowed(self $followed): self
    {
        if (!$this->followed->contains($followed)) {
            $this->followed[] = $followed;
        }

        return $this;
    }

    public function removeFollowed(self $followed): self
    {
        $this->followed->removeElement($followed);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(self $following): self
    {
        if (!$this->following->contains($following)) {
            $this->following[] = $following;
            $following->addFollowed($this);
        }

        return $this;
    }

    public function removeFollowing(self $following): self
    {
        if ($this->following->removeElement($following)) {
            $following->removeFollowed($this);
        }

        return $this;
    }
}
