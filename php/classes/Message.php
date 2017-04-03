<?php

namespace Deepdivedylan\DylanAndTonyFiesta;

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
	 * profile id of this Message
	 * @var string $messageProfileId
	 **/
	private $messageProfileId;
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
	 * constructor for this Message
	 *
	 * @param string $newMessageId new value of message id
	 * @param string $newMessageProfileId new value of message profile id
	 * @param string $newMessageContent new value of message content
	 * @param string|\DateTime $newMessageDateTime new value of message date time
	 */
	public function __construct(string $newMessageId, string $newMessageProfileId, string $newMessageContent, string $newMessageDateTime) {
		try {
			$this->setMessageId($newMessageId);
			$this->setMessageProfileId($newMessageProfileId);
			$this->setMessageContent($newMessageContent);
			$this->setMessageDateTime($newMessageDateTime);
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
	 * gets the Message by message id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $messageId message id to search for
	 * @return Message|null Message found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getMessageByMessageId(\PDO $pdo, string $messageId) : ?Message {
		// sanitize the message id before searching
		$messageId = trim($messageId);
		$messageId = filter_var($messageId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($messageId) === true) {
			throw(new \PDOException("message id is invalid"));
		}

		// create query template
		$query = "SELECT messageId, messageProfileId, messageContent, messageDateTime FROM message WHERE messageId = :messageId";
		$statement = $pdo->prepare($query);

		// bind the message id to the place holder in the template
		$parameters = ["messageId" => $messageId];
		$statement->execute($parameters);

		try {
			$message = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$message = new Message($row["messageId"], $row["messageProfileId"], $row["messageContent"], $row["messageDateTime"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		return($message);
	}

	/**
	 * gets Messages by message profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $messageProfileId message profile id to search for
	 * @return \SplFixedArray all Messages found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getMessageByMessageProfileId(\PDO $pdo, string $messageProfileId) : \SplFixedArray {
		// sanitize the message profile id before searching
		$messageProfileId = trim($messageProfileId);
		$messageProfileId = filter_var($messageProfileId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($messageProfileId) === true) {
			throw(new \PDOException("message profile id is invalid"));
		}

		// create query template
		$query = "SELECT messageId, messageProfileId, messageContent, messageDateTime FROM message WHERE messageProfileId = :messageProfileId";
		$statement = $pdo->prepare($query);

		// bind the message id to the place holder in the template
		$parameters = ["messageProfileId" => $messageProfileId];
		$statement->execute($parameters);

		$messages = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$message = new Message($row["messageId"], $row["messageProfileId"], $row["messageContent"], $row["messageDateTime"]);
				$messages[$messages->key()] = $message;
				$messages->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return($messages);
	}

	/**
	 * gets all Messages
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray all Messages found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getAllMessages(\PDO $pdo) : \SplFixedArray {
		// create query template
		$query = "SELECT messageId, messageProfileId, messageContent, messageDateTime FROM message";
		$statement = $pdo->prepare($query);

		$messages = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$message = new Message($row["messageId"], $row["messageProfileId"], $row["messageContent"], $row["messageDateTime"]);
				$messages[$messages->key()] = $message;
				$messages->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return($messages);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["messageDateTime"] = round(floatval($this->messageDateTime->format("U.u")) * 1000);
		return($fields);
	}
}