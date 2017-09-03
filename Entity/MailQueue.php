<?php

namespace Hessam\MailQueueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailQueue
 *
 * @ORM\Table(name="mail_queue",indexes={
*       @ORM\Index(name="bugloos_mail_queue_queued_at_idx", columns={"queued_at"}),
 *      @ORM\Index(name="bugloos_mail_queue_send_at_idx", columns={"send_at"})
 *   })
 * @ORM\Entity(repositoryClass="Hessam\MailQueueBundle\Repository\MailQueueRepository")
 */
class MailQueue {

  const STATUS_FAILED = -1;
  const STATUS_READY = 1;
  const STATUS_PROCESSING = 2;
  const STATUS_COMPLETE = 3;

  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var integer
   * @ORM\Column(name="status", type="integer")
   */
  private $status;

  /**
   * @var string
   *
   * @ORM\Column(name="sender", type="text")
   */
  private $sender;
  /**
   * @var string
   *
   * @ORM\Column(name="recipient", type="string", length=255)
   */
  private $recipient;
  /**
   * @var string
   *
   * @ORM\Column(name="subject", type="text", nullable=true)
   */
  private $subject;
  /**
 * @var string
 *
 * @ORM\Column(name="cc", type="text", nullable=true)
 */
  private $cc;
  /**
   * @var string
   *
   * @ORM\Column(name="bcc", type="text", nullable=true)
   */
  private $bcc;
  /**
   * @var string
   *
   * @ORM\Column(name="error_message", type="text", nullable=true)
   */
  private $errorMessage;

  /**
   * @var string
   * @ORM\Column(name="message",type="text",nullable=true)
   */
  private $message;

  /**
   * @var string
   * @ORM\Column(name="environment",type="string")
   */
  private $environment;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="queued_at", type="datetime", nullable=true)
   */
  private $queuedAt;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="send_at", type="datetime", nullable=true)
   */
  private $sendAt;


  /**
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getSender() {
    return $this->sender;
  }

  /**
   * @param string $sender
   */
  public function setSender($sender) {
    $this->sender = $sender;
  }

  /**
   * @return string
   */
  public function getRecipient() {
    return $this->recipient;
  }

  /**
   * @param string $recipient
   */
  public function setRecipient($recipient) {
    $this->recipient = $recipient;
  }

  /**
   * @return string
   */
  public function getSubject() {
    return $this->subject;
  }

  /**
   * @param string $subject
   */
  public function setSubject($subject) {
    $this->subject = $subject;
  }

  /**
   * @return string
   */
  public function getCc() {
    return $this->cc;
  }

  /**
   * @param string $cc
   */
  public function setCc($cc) {
    $this->cc = $cc;
  }

  /**
   * @return string
   */
  public function getBcc() {
    return $this->bcc;
  }

  /**
   * @param string $bcc
   */
  public function setBcc($bcc) {
    $this->bcc = $bcc;
  }

  /**
   * @return string
   */
  public function getErrorMessage() {
    return $this->errorMessage;
  }

  /**
   * @param string $errorMessage
   */
  public function setErrorMessage($errorMessage) {
    $this->errorMessage = $errorMessage;
  }

  /**
   * @return \DateTime
   */
  public function getQueuedAt() {
    return $this->queuedAt;
  }

  /**
   * @param \DateTime $queuedAt
   */
  public function setQueuedAt($queuedAt) {
    $this->queuedAt = $queuedAt;
  }


  /**
   * @return \DateTime
   */
  public function getSendAt() {
    return $this->sendAt;
  }

  /**
   * @param \DateTime $sendAt
   */
  public function setSendAt($sendAt) {
    $this->sendAt = $sendAt;
  }



  /**
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * @param string $message
   */
  public function setMessage($message) {
    $this->message = $message;
  }

  /**
   * @return integer
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @param integer $status
   */
  public function setStatus($status) {
    $this->status = $status;
  }

  /**
   * @return string
   */
  public function getEnvironment() {
    return $this->environment;
  }

  /**
   * @param string $environment
   */
  public function setEnvironment($environment) {
    $this->environment = $environment;
  }


}

