<?php namespace Jos;

use ProcessWire\SimpleContactForm as SCF;
use ProcessWire\WireCSRFException;

 /**
 * Class SpamProtection
 *
 * @author Tabea David <info@justonestep.de>
 * @copyright Copyright (c) 2017
 * @filesource
 */

/**
 * Class SpamProtection
 */
class SpamProtection extends \ProcessWire\Wire {

  /**
   * User agents marked as spam
   */
  const USER_AGENTS = '#w3c|google|slurp|msn|yahoo|y!j|altavista|ask|spider|search|bot|crawl|usw#i';

  /**
   * boolean isSpam
   */
  protected static $isSpam = false;

  /**
   * Error messages used in log file
   */
  protected static $errorMessages = array(
    'default' => 'An error occured.',
    'token' => 'CSRF Token validation failed.',
    'honeypot' => 'Honeypot field was filled.',
    'numberOfFields' => 'Number of fields does not match.',
    'userAgent' => 'User Agent is not allowed.',
    'httpParams' => 'User Agent and HTTP Referer are empty.',
    'timeRange' => 'Date difference is out of range.',
    'ipAddress' => 'This IP address was already marked as spam.',
    'numberOfSubmits' => 'This IP address submitted this form too often.'
  );

  /**
   * Is valid checks
   * Not depending on whether messages should be saved
   */
  protected static $isValidChecks = array(
    'validToken',
    'validHoneypot',
    'validNumberOfFields',
    'validUserAgent',
    'validHttpParams',
    'validTimeRange'
  );

  /**
   * Is valid save messages checks
   * Depending on whether messages should be saved
   */
  protected static $isValidSMChecks = array(
    'validIpAddress',
    'validNumberOfSubmits'
  );

  /**
   * Construct
   */
  public function __construct() {
    $this->setLogFile();
    $this->currentIp = $_SERVER['REMOTE_ADDR'];
  }

  /**
   * Set log file
   */
  public function setLogFile() {
    $this->scfLog = strtolower(SCF::CLASS_NAME . '-log');
  }

  /**
   * Set number of inputs to compare with
   *
   * @param integer $count
   * @return SpamProtection
   */
  public function setCount($count) {
    $this->count = (int)$count;
    return $this;
  }

  /**
   * Set time range a submission is valid
   *
   * @param integer $min
   * @param integer $max
   * @return SpamProtection
   */
  public function setTimeRange($min, $max) {
    $this->timeRange = (object)array(
      'min' => $min,
      'max' => $max
    );
    return $this;
  }

  /**
   * Set whether messages should be saved
   *
   * @param boolean $saveMessages
   * @return SpamProtection
   */
  public function setSaveMessages($saveMessages) {
    $this->saveMessages = $saveMessages;
    return $this;
  }

  /**
   * Set ip addresses which should be excluded
   *
   * @param string $excludeIpAdresses
   * @return SpamProtection
   */
  public function setExcludeIpAdresses($excludeIpAdresses) {
    $this->excludeIpAdresses = explode(',', $excludeIpAdresses);
    return $this;
  }

  /**
   * Set maximum number of submits per day
   *
   * @param string $numberOfSubmitsPerDay
   * @return SpamProtection
   */
  public function setNumberOfSubmitsPerDay($numberOfSubmitsPerDay) {
    $this->numberOfSubmitsPerDay = $numberOfSubmitsPerDay;
    return $this;
  }

  /**
   * Whether spam was detected
   *
   * @return boolean
   */
  public function isSpam() {
    return self::$isSpam;
  }

  /**
   * Get random animal to build error message
   *
   * @return string
   */
  public function getAnimal() {
    $animals = array(
      $this->_('monkey'),
      $this->_('squirrel'),
      $this->_('giraffe'),
      $this->_('marmot')
    );

    return $animals[array_rand($animals)];
  }

  /**
   * Get random fruit to build error message
   *
   * @return string
   */
  public function getFruit() {
    $fruits = array(
      $this->_('strawberry'),
      $this->_('banana'),
      $this->_('peanut'),
      $this->_('blueberry')
    );

    return $fruits[array_rand($fruits)];
  }

  /**
   * Set whether the request is marked as spam
   *
   * @param boolean $isSpam
   */
  protected function setIsSpam($isSpam = true) {
    self::$isSpam = $isSpam;
  }

  /**
   * Add log entry
   *
   * @param string $key
   */
  protected function addLogEntry($key) {
    $this->log->save($this->scfLog, "[FAILURE] {$this->getErrorMessage($key)} IP: {$this->currentIp}");
  }

  /**
   * Get specific error message
   *
   * @param string $key
   * @return string
   */
  protected function getErrorMessage($key) {
    if (!array_key_exists($key, self::$errorMessages)) $key = 'default';
    return self::$errorMessages[$key];
  }

  /**
   * Check CSRF token
   */
  protected function validToken() {
    try {
      $this->session->CSRF->validate();
    } catch (WireCSRFException $e) {
      $this->setIsSpam();
      $this->addLogEntry('token');
    }
  }

  /**
   * Check if the honeypot field was filled
   */
  protected function validHoneypot() {
    if ($this->input->post->{'scf-website'}) {
      $this->setIsSpam();
      $this->addLogEntry('honeypot');
    }
  }

  /**
   * Check if the number of fields match
   */
  protected function validNumberOfFields() {
    if (count($this->input->post) !== $this->count) {
      $this->setIsSpam();
      $this->addLogEntry('numberOfFields');
    }
  }

  /**
   * Check the user agent
   */
  protected function validUserAgent() {
    if (preg_match(self::USER_AGENTS, $_SERVER['HTTP_USER_AGENT'])) {
      $this->setIsSpam();
      $this->addLogEntry('userAgent');
    }
  }

  /**
   * Check http referrer and user agent
   */
  protected function validHttpParams() {
    if ($_SERVER['HTTP_REFERER'] === '' && $_SERVER['HTTP_USER_AGENT'] === '') {
      $this->setIsSpam();
      $this->addLogEntry('httpParams');
    }
  }

  /**
   * Check whether the form was submitted within a certain time range
   */
  protected function validTimeRange() {
    $date = (int)$this->input->post->{'scf-date'};
    $dateDiff = $date ? time() - $date : 0;
    if ($dateDiff <= $this->timeRange->min || $dateDiff >= $this->timeRange->max) {
      $this->setIsSpam();
      $this->addLogEntry('timeRange');
    }
  }

  /**
   * Check whether the ip address was marked as spam
   */
  protected function validIpAddress() {
    $spamIpPages = $this->pages->find('template=' . SCF::SM_TEMPLATE_NAME . ', scf_spamIp!=');
    if (!$spamIpPages->count()) return;

    foreach ($spamIpPages as $spamIpPage) {
      if ($spamIpPage->scf_ip === $this->currentIp) {
        $this->setIsSpam();
        $this->addLogEntry('ipAddress');
        break;
      }
    }
  }

  /**
   * Check how often the form is allowed to be submitted by a single IP address
   */
  protected function validNumberOfSubmits() {
    $dateSub = new \DateTime();
    $dateSub->sub(new \DateInterval('P1D'));
    $selector = 'template=' . SCF::SM_TEMPLATE_NAME . ", scf_ip={$this->currentIp}, scf_date>={$dateSub->getTimestamp()}";
    $totalLast24h = $this->pages->find($selector)->count();

    if ($totalLast24h >= $this->numberOfSubmitsPerDay) {
      $this->setIsSpam();
      $this->addLogEntry('numberOfSubmits');
    }
  }

  /**
   * Validates the form
   *
   * @return SimpleContactForm
   */
  public function validate() {
    foreach (self::$isValidChecks as $isValid) {
      $this->$isValid();
      if (self::$isSpam) break;
    }

    // additional checks only if save messages feature is turned on
    if (!$this->isSpam() && $this->saveMessages) {
      if (!in_array($this->currentIp, $this->excludeIpAdresses)) {
        foreach (self::$isValidSMChecks as $isValid) {
          $this->$isValid();
          if (self::$isSpam) break;
        }
      }
    }

    return $this;
  }

}
