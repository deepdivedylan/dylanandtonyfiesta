<?php
namespace Deepdivedylan\DylanAndTonyFiesta\Test;

use Deepdivedylan\DylanAndTonyFiesta\{Media, Message, Profile};

require_once(dirname(__DIR__) . "/autoload.php");

class MediaTest extends DylanAndTonyFiestaTest {
	/**
	 * valid media id to use
	 * @var string $VALID_MEDIAID
	 **/
	protected $VALID_MEDIAID = "817277825208094720";
	/**
	 * valid media type to use
	 * @var string $VALID_MEDIATYPE
	 **/
	protected $VALID_MEDIATYPE = "image/jpeg";
	/**
	 * valid media url to use
	 * @var string $VALID_MEDIAURL
	 **/
	protected $VALID_MEDIAURL = "https://pbs.twimg.com/media/C8cBt21UwAEPdmt.jpg";
	/**
	 * profile to own the message
	 * @var Profile $profile
	 **/
	protected $profile = null;
	/**
	 * message to own the media
	 * @var Message $message
	 **/
	protected $message = null;

	public function setUp() {
		$this->profile = new Profile("847297430630522880", "SenatorArlo", "T");
		$this->profile->insert($this->getPDO());

		$this->message = new Message("848649042821341185", $this->profile->getProfileId(), "Senator Arlo endorses this marriage and can't wait to get drunk off Romulan ale at #dylanandtonyfiesta", new \DateTime());
		$this->message->insert($this->getPDO());
	}

	public function testInsertValidMedia() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("media");

		$media = new Media($this->VALID_MEDIAID, $this->message->getMessageId(), $this->VALID_MEDIATYPE, $this->VALID_MEDIAURL);
		$media->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("media"));
		$pdoMedia = Media::getMediaByMediaId($this->getPDO(), $this->VALID_MEDIAID);
		$this->assertEquals($pdoMedia->getMediaId(), $media->getMediaId());
		$this->assertEquals($pdoMedia->getMediaMessageId(), $media->getMediaMessageId());
		$this->assertEquals($pdoMedia->getMediaType(), $media->getMediaType());
		$this->assertEquals($pdoMedia->getMediaUrl(), $media->getMediaUrl());
	}
}