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
 * Class SpamProtection
 */
class SpamProtection extends \ProcessWire\Wire {

  const USER_AGENTS = '#w3c|google|slurp|msn|yahoo|y!j|altavista|ask|spider|search|bot|crawl|usw#i';

  /**
   * boolean isSpam
   */
  protected static $isSpam = false;

  protected static $errorMessages = array(
    'default' => 'An error occured.',
    'token' => 'CSRF Token validation failed.',
    'honeypot' => 'Honeypot field was filled.',
    'numberOfFields' => 'Number of fields does not match.',
    'userAgent' => 'User Agent is not allowed.',
    'httpParams' => 'User Agent and HTTP Referer are empty.',
    'timeRange' => 'Date difference is out of range.'
  );

  protected static $isValidChecks = array(
    'validToken',
    'validHoneypot',
    'validNumberOfFields',
    'validUserAgent',
    'validHttpParams',
    'validTimeRange'
  );

  /**
   * construct
   */
  public function __construct() {
    $this->setLogFile();
  }

  public function setLogFile() {
    $this->scfLog = strtolower(SimpleContactForm::CLASS_NAME . '-log');
  }

  public function setCount($count) {
    $this->count = (int)$count;
  }

  public function setTimeRange($min, $max) {
    $this->timeRange = (object)array(
      'min' => $min,
      'max' => $max
    );
  }

  public function isSpam() {
    return self::$isSpam;
  }

  public function getAnimal() {
    $animals = array(
      $this->_('monkey'),
      $this->_('squirrel'),
      $this->_('giraffe'),
      $this->_('marmot')
    );

    return $animals[array_rand($animals)];
  }

  public function getFruit() {
    $fruits = array(
      $this->_('strawberry'),
      $this->_('banana'),
      $this->_('peanut'),
      $this->_('blueberry')
    );

    return $fruits[array_rand($fruits)];
  }

  protected function setIsSpam($isSpam = true) {
    self::$isSpam = $isSpam;
  }

  protected function addLogEntry($key) {
    $this->log->save($this->scfLog, '[FAILURE] ' . $this->getErrorMessage($key));
  }

  protected function getErrorMessage($key) {
    if (!array_key_exists($key, self::$errorMessages)) $key = 'default';
    return self::$errorMessages[$key];
  }

  protected function validToken() {
    try {
      $this->session->CSRF->validate();
    } catch (WireCSRFException $e) {
      $this->setIsSpam();
      $this->addLogEntry('token');
    }
  }

  protected function validHoneypot() {
    if ($this->input->post->{'scf-website'}) {
      $this->setIsSpam();
      $this->addLogEntry('honeypot');
    }
  }

  protected function validNumberOfFields() {
    if (count($this->input->post) !== $this->count) {
      $this->setIsSpam();
      $this->addLogEntry('numberOfFields');
    }
  }

  protected function validUserAgent() {
    if (preg_match(self::USER_AGENTS, $_SERVER['HTTP_USER_AGENT'])) {
      $this->setIsSpam();
      $this->addLogEntry('userAgent');
    }
  }

  protected function validHttpParams() {
    if ($_SERVER['HTTP_REFERER'] === '' && $_SERVER['HTTP_USER_AGENT'] === '') {
      $this->setIsSpam();
      $this->addLogEntry('httpParams');
    }
  }

  protected function validTimeRange() {
    $date = (int)$this->input->post->{'scf-date'};
    $dateDiff = $date ? time() - $date : 0;
    if ($dateDiff <= $this->timeRange->min || $dateDiff >= $this->timeRange->max) {
      $this->setIsSpam();
      $this->addLogEntry('timeRange');
    }
  }

  /**
   * Validates the form
   *
   * @return boolean
   */
  public function validate() {
    foreach (self::$isValidChecks as $isValid) {
      $this->$isValid();
      if (self::$isSpam) break;
    }


    // additional checks only if save messages feature is turned on
    // if (!self::$isSpam && $this->saveMessages) {
    //     // get all mail addresses marked as spam
    //     $receivedMessages = $this->pages->findOne('template=simple_contact_form_messages')->{$this->repeaterName};
    //     $currentIp = $_SERVER['REMOTE_ADDR'];
    //     $excludeIps = explode(',', $this->antiSpamExcludeIps);
    //
    //     // get ips and mail addresses marked as spam
    //     $spam = array('ips' => array(), 'mails' => array());
    //     $spamMailsMsgs = $receivedMessages->find('scf_spamMail!=');
    //     $spamIpsMsgs = $receivedMessages->find('scf_spamIp!=');
    //     foreach ($spamMailsMsgs as $spamMsg) { $spam['mails'][] = $spamMsg->scf_email; }
    //     foreach ($spamIpsMsgs as $spamMsg) { $spam['ips'][] = $spamMsg->scf_ip; }
    //
    //     if (!in_array($currentIp, $excludeIps) && $this->saveMessages) {
    //       // control how often the form is allowed to be submitted by a single IP address
    //       $dateSub = new DateTime();
    //       $dateSub->sub(new DateInterval('P1D'));
    //       $totalLast24h = $receivedMessages->find('scf_ip!="", scf_date>=' . $dateSub->getTimestamp() . ', scf_ip=' . $currentIp)->count();
    //
    //       if ($totalLast24h >= $this->antiSpamPerDay) {
    //         $spam = true;
    //         $this->log->save('[FAILURE] This IP address submitted this form too often.');
    //       }
    //     } elseif (in_array($currentIp, $spam['ips'])) {
    //       // check whether ip was already marked as spam
    //       $spam = true;
    //       $this->log->save("[FAILURE] This IP address $currentIp was already marked as spam.");
    //     } else {
    //       foreach ($this->emailFields as $emailField) {
    //         if (in_array($this->input->post->{$emailField}, $spam['mails'])) {
    //           // check whether mail address was already marked as spam
    //           $spam = true;
    //           $this->log->save('[FAILURE] This mail address ' . $this->input->post->{$emailField} . ' was already marked as spam.');
    //         }
    //       }
    //     }
    // }
  }

}
