<?php

namespace Hessam\MailQueueBundle\Spool;


use Hessam\MailQueueBundle\Entity\MailQueue;
use Doctrine\ORM\EntityManager;
use Swift_Mime_Message;
use Swift_Transport;

class DatabaseSpool extends \Swift_ConfigurableSpool {
  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private $manager;

  /**
   * @var string
   */
  protected $environment;
  /**
   * @var string
   */
  private $keep_sent_messages;
  /**
   * @var string
   */
  private $keep_time;

  /**
   * DatabaseSpool constructor.
   * @param \Doctrine\ORM\EntityManager $manager
   * @param  string $environment
   * @param string $keep_sent_messages
   * @param string $keep_time
   */
  public function __construct(EntityManager $manager,$environment,$keep_sent_messages,$keep_time) {
    $this->manager = $manager;
    $this->environment=$environment;
    $this->keep_sent_messages = $keep_sent_messages;
    $this->keep_time = $keep_time;
  }


  /**
   * Starts this Spool mechanism.
   */
  public function start() {
    // TODO: Implement start() method.
  }

  /**
   * Stops this Spool mechanism.
   */
  public function stop() {
    // TODO: Implement stop() method.
  }

  /**
   * Tests if this Spool mechanism has started.
   *
   * @return bool
   */
  public function isStarted() {
    return true;
  }

  /**
   * Queues a message.
   *
   * @param Swift_Mime_Message $message The message to store
   *
   * @return bool Whether the operation has succeeded
   */
  public function queueMessage(Swift_Mime_Message $message) {
    $mailQueue=new MailQueue();
    $from = $this->sanitizeAddresses(array_keys($message->getFrom()))[0];
    $recipient = $this->sanitizeAddresses(array_keys($message->getTo()));
    $mailQueue->setSubject($message->getSubject());
    $mailQueue->setSender($from);
    $mailQueue->setRecipient(implode(';', $recipient));
    if ($cc = $message->getCc()) {
      $mailQueue->setCc(implode(';', $this->sanitizeAddresses(array_keys($cc))));
    }

    if ($bcc = $message->getBcc()) {
      $mailQueue->setBcc(implode(';', $this->sanitizeAddresses(array_keys($bcc))));
    }
    $mailQueue->setQueuedAt(new \DateTime());
    $mailQueue->setMessage(serialize($message));
    $mailQueue->setStatus(MailQueue::STATUS_READY);
    $mailQueue->setEnvironment($this->environment);
    $this->manager->persist($mailQueue);
    $this->manager->flush();

    return true;
  }

  /**
   * Sends messages using the given transport instance.
   *
   * @param Swift_Transport $transport A transport instance
   * @param string[] $failedRecipients An array of failures by-reference
   *
   * @return int The number of sent emails
   */
  public function flushQueue(Swift_Transport $transport, &$failedRecipients = NULL) {
    if (!$transport->isStarted())
    {
      $transport->start();
    }
    $limit = $this->getMessageLimit();
    $limit = $limit > 0 ? $limit : null;
    $repository=$this->manager->getRepository(MailQueue::class);
    $emails = $repository->findBy(
      array("status" => MailQueue::STATUS_READY, "environment" => $this->environment),
      null,
      $limit
    );
    if (!count($emails)) {
      return 0;
    }
    $count = 0;
    $time = time();
    /** @var MailQueue $email */
    foreach ($emails as $email) {
      $email->setStatus(MailQueue::STATUS_PROCESSING);
      $this->manager->persist($email);
      $this->manager->flush();
      $message = unserialize($email->getMessage());
      $count += $transport->send($message, $failedRecipients);
      if ($this->keep_sent_messages === true) {
        $email->setStatus(MailQueue::STATUS_COMPLETE);
        $email->setSendAt(new \DateTime());
        $this->manager->persist($email);
      } else {
        $this->manager->remove($email);
      }
      $this->manager->flush();
      if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
        break;
      }
    }
    if ($this->keep_sent_messages === true && $this->keep_time) {
      $time = new \DateTime();
      $time->sub(new \DateInterval($this->keep_time));
      $emailsToDelete = $repository->findEmailsForDelete($time, $this->environment);
      foreach ($emailsToDelete as $email) {
        $this->manager->remove($email);
      }
      $this->manager->flush();
    }
    return $count;
  }


  /**
   * Sanitizes addresses and filters out invalid emails
   *
   * @param string[] $addresses
   *
   * @return string[]
   */
  protected function sanitizeAddresses($addresses)
  {
    // returns resulting array, excluding invalid addresses
    return array_filter(array_map(
      function($email) {
        // sanitizes emails and excludes the invalid ones
        return filter_var(filter_var(trim($email), FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) ?: false;
      },
      (array) $addresses
    ));
  }
}