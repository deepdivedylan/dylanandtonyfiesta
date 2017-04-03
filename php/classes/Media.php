<?php

namespace Deepdivedylan\Dylanandtonyfiesta;

/**
 * Media Class
 *
 * Generic media container for images and videos on social media
 *
 * @package Deepdivedylan\Dylanandtonyfiesta
 **/
class Media {
	/**
	 * id of this Media from the service
	 * @var string $mediaId
	 **/
	private $mediaId;
	/**
	 * id of the Message this Media is attached to
	 * @var string $mediaMessageId
	 **/
	private $mediaMessageId;
	/**
	 * MIME type of this Media
	 * @var string $mediaType
	 **/
	private $mediaType;
	/**
	 * URL of this Media
	 * @var string $mediaUrl
	 **/
	private $mediaUrl;

	/**
	 * constructor for this Media
	 *
	 * @param string $newMediaId new value of media id
	 * @param string $newMediaMessageId new value media message id
	 * @param string $newMediaType new value of media type
	 * @param string $newMediaUrl new value of media url
	 * @throws \InvalidArgumentException if values are empty or insecure
	 * @throws \RangeException if values are too large
	 * @throws \TypeError if type declarations fail
	 **/
	public function __construct(string $newMediaId, string $newMediaMessageId, string $newMediaType, string $newMediaUrl) {
		try {
			$this->setMediaId($newMediaId);
			$this->setMediaMessageId($newMediaMessageId);
			$this->setMediaType($newMediaType);
			$this->setMediaUrl($newMediaUrl);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage()));
		}
	}

	/**
	 * accessor method for media id
	 *
	 * @return string current value of media id
	 **/
	public function getMediaId(): string {
		return($this->mediaId);
	}



	/**
	 * mutator method for media id
	 *
	 * @param string $newMediaId new value of media id
	 * @throws \InvalidArgumentException if media id is empty
	 * @throws \RangeException if media id is too large
	 **/
	public function setMediaId(string $newMediaId) {
		$newMediaId = trim($newMediaId);
		$newMediaId = filter_var($newMediaId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newMediaId) === true) {
			throw(new \InvalidArgumentException("media id cannot be empty"));
		}

		if(strlen($newMediaId) > 18) {
			throw(new \RangeException("media id is too large"));
		}

		$this->mediaId = $newMediaId;
	}

	/**
	 * accessor method for media message id
	 *
	 * @return string current value of media message id
	 **/
	public function getMediaMessageId(): string {
		return($this->mediaMessageId);
	}

	/**
	 * mutator method for media message id
	 *
	 * @param string $newMediaMessageId new value of media message id
	 * @throws \InvalidArgumentException if media message id is empty
	 * @throws \RangeException if media message id is too large
	 **/
	public function setMediaMessageId(string $newMediaMessageId) {
		$newMediaMessageId = trim($newMediaMessageId);
		$newMediaMessageId = filter_var($newMediaMessageId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newMediaMessageId) === true) {
			throw(new \InvalidArgumentException("media message id cannot be empty"));
		}

		if(strlen($newMediaMessageId) > 18) {
			throw(new \RangeException("media message id is too large"));
		}
		
		$this->mediaMessageId = $newMediaMessageId;
	}

	/**
	 * accessor method for media type
	 *
	 * @return string current value of media type
	 **/
	public function getMediaType(): string {
		return($this->mediaType);
	}

	/**
	 * mutator method media type
	 *
	 * @param string $newMediaType new value of media type
	 * @throws \InvalidArgumentException if media type is invalid
	 **/
	public function setMediaType(string $newMediaType) {
		$validMediaTypes = ["image/jpeg", "image/png", "video/mp4"];
		$newMediaType = trim($newMediaType);
		if(in_array($newMediaType, $validMediaTypes) === false) {
			throw(new \InvalidArgumentException("invalid media type"));
		}

		$this->mediaType = $newMediaType;
	}

	/**
	 * accessor method for media url
	 *
	 * @return string current value of media url
	 **/
	public function getMediaUrl(): string {
		return($this->mediaUrl);
	}

	/**
	 * mutator method for media url
	 * 
	 * @param string $newMediaUrl new value of media url
	 * @throws \InvalidArgumentException if media url is empty or insecure
	 * @throws \RangeException if media url is too large
	 **/
	public function setMediaUrl(string $newMediaUrl) {
		$newMediaUrl = trim($newMediaUrl);
		$newMediaUrl = filter_var($newMediaUrl, FILTER_VALIDATE_URL);
		if(empty($newMediaUrl) === true) {
			throw(new \InvalidArgumentException("media url cannot be empty"));
		}

		if(strlen($newMediaUrl) > 64) {
			throw(new \RangeException("media url is too large"));
		}
		$this->mediaUrl = $newMediaUrl;
	}
}