<?php

namespace Deepdivedylan\Dylanandtonyfiesta;

require_once(__DIR__ . "/autoload.php");

/**
 * Profile Class
 *
 * Generic message container for messages on social media
 *
 * @package Deepdivedylan\Dylanandtonyfiesta
 **/
class Message implements \JsonSerializable {
	use ValidateDate;

	/**
	 * id of this Message from the service
	 * @var string $messageId
	 **/
	private $messageId;
	/**
	 * content of this Message
	 * @var string $messageContent
	 **/
	private $messageContent;
	/**
	 * date and time of this Message
	 * @var \DateTime $messageDateTime
	 **/
	private $messageDateTime;
	/**
	 * profile id of this Message
	 * @var string $messageProfileId
	 **/
	private $messageProfileId;

	/**
	 * constructor for this Message
	 *
	 * @param string $newMessageId new value of message id
	 * @param string $newMessageContent new value of message content
	 * @param string $newMessageProfileId new value of message profile id
	 * @throws \InvalidArgumentException if values are empty or insecure
	 * @throws \RangeException if values are too large
	 * @throws \TypeError if type declarations fail
	 **/
	public function __construct($newMessageId, $newMessageContent, $newMessageProfileId) {
		try {
			$this->setMessageId($newMessageId);
			$this->setMessageContent($newMessageContent);
			$this->setMessageProfileId($newMessageContent);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}


	/**
	 * accessor method for message id
	 * 
	 * @return string current value of message id
	 */
	public function getMessageId(): string {
		return($this->messageId);
	}

	/**
	 * mutator method for message id
	 * 
	 * @param string $newMessageId new value of message id
	 * @throws \InvalidArgumentException if message id is empty
	 * @throws \RangeException if message id is too large
	 **/
	public function setMessageId(string $newMessageId) {
		$newMessageId = trim($newMessageId);
		$newMessageId = filter_var($newMessageId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newMessageId) === true) {
			throw(new \InvalidArgumentException("message id cannot be empty"));
		}

		if(strlen($newMessageId) > 18) {
			throw(new \RangeException("message id is too large"));
		}

		$this->messageId = $newMessageId;
	}

	/**
	 * accessor method for message content
	 *
	 * @return string current value of message content
	 **/
	public function getMessageContent(): string {
		return($this->messageContent);
	}

	/**
	 * mutator method for message content
	 * 
	 * @param string $newMessageContent new value of message content
	 * @throws \InvalidArgumentException if message content is empty
	 * @throws \RangeException if message content is too large
	 **/
	public function setMessageContent(string $newMessageContent) {
		$newMessageContent = trim($newMessageContent);
		$newMessageContent = filter_var($newMessageContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newMessageContent) === true) {
			throw(new \InvalidArgumentException("message content cannot be empty"));
		}

		if(strlen($newMessageContent) > 140) {
			throw(new \RangeException("message content is too large"));
		}
		
		$this->messageContent = $newMessageContent;
	}

	/**
	 * accessor method for message date time
	 *
	 * @return \DateTime current value of message date time
	 **/
	public function getMessageDateTime(): \DateTime {
		return($this->messageDateTime);
	}

	/**
	 * mutator method for message date time
	 *
	 * @param string|\DateTime $newMessageDateTime new value of message date time
	 **/
	public function setMessageDateTime($newMessageDateTime) {
		try {
			$newMessageDateTime = self::validateDateTime($newMessageDateTime);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		$this->messageDateTime = $newMessageDateTime;
	}

	/**
	 * mutator method for message profile id
	 *
	 * @return string current value of message profile id
	 **/
	public function getMessageProfileId(): string {
		return($this->messageProfileId);
	}

	/**
	 * mutator method for message profile id
	 *
	 * @param string $newMessageProfileId new value of message profile id
	 * @throws \InvalidArgumentException if message profile id is empty
	 * @throws \RangeException if message profile id is too large
	 **/
	public function setMessageProfileId(string $newMessageProfileId) {
		$newMessageProfileId = trim($newMessageProfileId);
		$newMessageProfileId = filter_var($newMessageProfileId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newMessageProfileId) === true) {
			throw(new \InvalidArgumentException("message profile id cannot be empty"));
		}

		if(strlen($newMessageProfileId) > 18) {
			throw(new \RangeException("message profile id is too large"));
		}
		
		$this->messageProfileId = $newMessageProfileId;
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}