<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
  public function indexAction(){
    $message = \Swift_Message::newInstance()
      ->setSubject("test")
      ->setFrom(["teamordersystem@gmail.com"=>'Duijn Teamsport'])
      ->setTo("mahmood.bazdar@gmail.com")
      ->setBody(
        "<h1>Email content</h1>",
        'text/html; charset=iso-8859-1'
      );

    $message->getHeaders()->addTextHeader('X-Sender','Duijn Teamsport < system@teamsportwinkel.nl >');
    $message->getHeaders()->addTextHeader('X-Mailer','PHP/' . phpversion());
    $message->getHeaders()->addTextHeader('X-Priority',1);
    $message->getHeaders()->addTextHeader('Return-Path','info@teamsportwinkel.nl');
    $message->getHeaders()->addTextHeader('MIME-Version','1.0');
    $message->getHeaders()->addTextHeader('Return-Path','info@teamsportwinkel.nl');
    $this->get("swiftmailer.mailer")->send($message);

    return new Response("here");
  }
}
