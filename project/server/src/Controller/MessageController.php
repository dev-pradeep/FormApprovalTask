<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Message;
use App\Service\MessageService;


class MessageController extends AbstractController
{
    const CRON_TOKEN      = 'azertyx_token';
    const APPROVED_EMAIL  = "mail/approved_message.txt.twig";
    const REJECTED_EMAIL  = "mail/rejected_message.txt.twig";
    
    private $messageService;
    
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }
    
    //path="api/admin/messages/approve/{id}",
    /**
     * @Route(
     *     name="approve_message",
     *     path="api/messages/approve/{id}",
     *     methods={"POST"},
     *     defaults={
     *       "_controller"="\App\Controller\MessageController::approveMessage",
     *       "_api_resource_class"="App\Entity\Message",
     *       "_api_item_operation_name"="approveMessage"
     *     }
     *   )
     */
    public function approveMessage(Message $data, MessageService $messageService)
    {
        if ($messageService->approveMessage($data)) {
            return $this->json([
                'id' => $data->getId(),
                'success' => true,
            ]);
        } else {
            return $this->json([
                'id' => $data->getId(),
                'success' => false,
            ]);
        }
    }

    /**
     * @Route(
     *     name="reject_message",
     *     path="messages/reject/{token}",
     *     methods={"GET"},
     *     defaults={
     *       "_controller"="\App\Controller\MessageController::rejectMessages",
     *     }
     *   )
     */    
    public function rejectMessages(string $token)
    {
        if ($token==self::CRON_TOKEN) {
            if ($this->messageService->rejectMessages()) {
                return new Response('Successfuly rejected some messages');
            }
            return new Response("No messages to reject.");
        }
        return new Response("You have no rights to do this.");
    }
}
