<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($registry, Message::class);
    }

    public function rejectMessages()
    {
        $sql ='SELECT id, email FROM messages WHERE status ='.Message::PENDING_STATUS.'
            AND (TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP(), created_at))/60)>'.Message::REJECTION_DELAY
        ;

        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $emails = $stmt->fetchAll();
        
        if (count($emails)) {
            $sql = 'UPDATE messages set status = '.Message::REJECTED_STATUS.' WHERE status ='.Message::PENDING_STATUS.'
                AND (TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP(), created_at))/60)>'.Message::REJECTION_DELAY
            ;

            $stmt = $this->em->getConnection()->prepare($sql);            
            if ($stmt->execute()) {
                return $emails;
            }
        }
        
        return false;
    }


}
