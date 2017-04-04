<?php
namespace Deepdivedylan\DylanAndTonyFiesta\Test;

use Deepdivedylan\DylanAndTonyFiesta\{Message, Profile};

require_once(dirname(__DIR__) . "/autoload.php");

class MessageTest extends DylanAndTonyFiestaTest {
	/**
	 * valid message id to use
	 * @var string $VALID_MESSAGEID
	 **/
	protected $VALID_MESSAGEID = "848649042821341185";
	/**
	 * valid message content to use
	 * @var string $VALID_MESSAGECONTENT
	 **/
	protected $VALID_MESSAGECONTENT = "Senator Arlo endorses this marriage and can't wait to get drunk off Romulan ale at #dylanandtonyfiesta";
	/**
	 * valid message date and time to use
	 * @var \DateTime $VALID_MESSAGEDATETIME
	 **/
	protected $VALID_MESSAGEDATETIME = null;
	/**
	 * profile to own test message
	 * @var Profile $profile
	 **/
	protected $profile = null;

	/**
	 * setup parent objects
	 **/
	public function setUp() {
		$this->profile = new Profile("847297430630522880", "SenatorArlo", "T");
		$this->profile->insert($this->getPDO());
		$this->VALID_MESSAGEDATETIME = new \DateTime();
	}

	/**
	 * test inserting a valid message
	 **/
	public function testInsertValidMessage() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("message");

		$message = new Message($this->VALID_MESSAGEID, $this->profile->getProfileId(), $this->VALID_MESSAGECONTENT, $this->VALID_MESSAGEDATETIME);
		$message->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("message"));
		$pdoMessage = Message::getMessageByMessageId($this->getPDO(), $this->VALID_MESSAGEID);
		$this->assertEquals($pdoMessage->getMessageId(), $message->getMessageId());
		$this->assertEquals($pdoMessage->getMessageProfileId(), $message->getMessageProfileId());
		$this->assertEquals($pdoMessage->getMessageContent(), $message->getMessageContent());
		$this->assertEquals($pdoMessage->getMessageDateTime(), $message->getMessageDateTime());
	}
}