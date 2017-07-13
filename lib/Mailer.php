<?php namespace Jos;

/**
 * Class Mailer
 *
 * @author Tabea David <info@justonestep.de>
 * @copyright Copyright (c) 2017
 * @filesource
 */

/**
 * Class Mailer
 */
class Mailer extends \ProcessWire\Wire {

  /**
   * construct
   *
   * @param string $to
   * @param string $from
   * @param string $replyTo
   * @param string $subject
   * @param string $body
   */
  public function __construct($to, $from, $replyTo, $subject, $body) {
    $this->subject = $subject;
    $this->body = $body;

    $this->to = array();
    foreach (explode(',', $to) as $value) {
      list($toEmail, $toName) = $this->extractEmailAndName($value);
      $this->to[] = "$toName <$toEmail>";
    }

    list($fromEmail, $fromName) = $this->extractEmailAndName($from);
    $this->from = "$fromName <$fromEmail>";

    if ($replyTo) {
      list($replyToEmail, $replyToName) = $this->extractEmailAndName($replyTo);
      $this->replyTo = "$replyToName <$replyToEmail>";
    } else {
      $this->replyTo = '';
    }
  }

  /**
   * extract email from name
   * substitute umlaute
   *
   * @param string $email
   * @return array
   */
  protected function extractEmailAndName($email) {
    $name = '';
    if (strpos($email, '<') !== false && strpos($email, '>') !== false) {
      // email has separate from name and email
      if (preg_match('/^(.*?)<([^>]+)>.*$/', $email, $matches)) {
        $name = preg_replace(
          array('/ä/', '/ö/', '/ü/', '/Ä/', '/Ö/', '/Ü/','/ß/'),
          array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss'),
          $matches[1]
        );
        $email = $matches[2];
      }
    }

    return array($email, $name);
  }

  /**
   * send mail
   *
   * @return boolean
   */
  public function send() {
    $wireMail = \ProcessWire\wireMail(); // don't use `new WireMail()` which bypasses WireMailSMTP

    $wireMail->to($this->to);
    $wireMail->from($this->from);
    $wireMail->subject($this->subject);
    $wireMail->body($this->body);

    if ($this->replyTo) $wireMail->header('Reply-To', $this->replyTo);

    return $wireMail->send();
  }

}
