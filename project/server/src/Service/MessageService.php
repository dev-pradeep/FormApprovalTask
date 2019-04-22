<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Message;
use App\Controller\MailController;

class MessageService
{    
    const APPROVED_EMAIL  = "mail/approved_message.txt.twig";
    const REJECTED_EMAIL  = "mail/rejected_message.txt.twig";
   
    private $em;
    private $mailController;

    public function __construct(EntityManagerInterface $em, MailController $mailController)
    {
        $this->em = $em;
        $this->mailController = $mailController;
    }

    public function approveMessage(Message $message)
    {
        if ($message->setIsApproved(true) && $message->setStatus(Message::APPROVED_STATUS)) {
            $this->em->persist($message);
            $this->em->flush();

            if ($this->mailController->sendMail($message->getEmail(), self::APPROVED_EMAIL)) {
                return true;
            }
        }

        return false;
    }

    public function rejectMessages() 
    {
        $emails = $this->em->getRepository(Message::class)->rejectMessages();  

        if ($emails && count($emails)>0) {
            foreach($emails as $email) {
                $this->mailController->sendMail($email['email'], self::REJECTED_EMAIL);
            }

            return true;
        }

        return false;
    }
}