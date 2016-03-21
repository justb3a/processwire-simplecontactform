<?php namespace Jos;

use \ProcessWire\SimpleContactForm;

/**
 * Class SpamProtection
 *
 * @author Tabea David <info@justonestep.de>
 * @version 1.0.0
 * @copyright Copyright (c) 2016
 * @filesource
 */

/**
 * Class Mailer
 */
class Mailer extends \ProcessWire\Wire {

  /**
   * construct
   */
  public function __construct($to, $from, $subject, $body) {
    $this->subject = $subject;
    $this->body = $body;

    $this->to = array();
    foreach (explode(',', $to) as $value) {
      list($toEmail, $toName) = $this->extractEmailAndName($value);
      $this->to[] = "$toName <$toEmail>";
    }

    list($fromEmail, $fromName) = $this->extractEmailAndName($from);
    $this->from = "$fromName <$fromEmail>";

  }

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

  public function send() {
    $wireMail = \ProcessWire\wireMail(); // don't use `new WireMail()` which bypasses WireMailSMTP

    $wireMail->to($this->to);
    $wireMail->from($this->from);
    $wireMail->subject($this->subject);
    $wireMail->body($this->body);
    return $wireMail->send();
  }

}
