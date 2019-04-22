<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail($to_email, $template)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo($to_email)
            ->setBody(
                $this->renderView(
                $template
                )
            )
        ;

        try {
            $result = $this->mailer->send($message);
        } catch (\Swift_TransportException $e) {
            throw new \Swift_TransportException();
        } catch (\Exception $e) {
            throw new \Exception();
        }

        return true;
    }
}
