<?php

namespace Deepdivedylan\Dylanandtonyfiesta;

/**
 * Profile Class
 *
 * Generic profile container for authors of social media
 *
 * @package Deepdivedylan\Dylanandtonyfiesta
 **/
class Profile {
	/**
	 * id of this Profile from the service
	 * @var string $profileId
	 **/
	private $profileId;

	/**
	 * username of this Profile
	 * @var string $profileName
	 **/
	private $profileName;

	/**
	 * one character flag for the service this Profile uses
	 * @var string $profileService
	 **/
	private $profileService;

	/**
	 * constructor for this Profile
	 *
	 * @param string $newProfileId
	 * @param string $newProfileName
	 * @param string $profileService
	 */
	public function __construct(string $newProfileId, string $newProfileName, string $newProfileService) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileName($newProfileName);
			$this->setProfileService($newProfileService);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage()));
		}
	}


	/**
	 * accessor method for profile id
	 *
	 * @return string current value of profile id
	 **/
	public function getProfileId(): string {
		return($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param string $newProfileId new value of profile id
	 * @throws \InvalidArgumentException if profile id is empty
	 * @throws \RangeException if profile id is too large
	 **/
	public function setProfileId(string $newProfileId) {
		$newProfileId = trim($newProfileId);
		$newProfileId = filter_var($newProfileId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileId) === true) {
			throw(new \InvalidArgumentException("profile id cannot be empty"));
		}

		if(strlen($newProfileId) > 18) {
			throw(new \RangeException("profile id is too large"));
		}

		$this->profileId = $newProfileId;
	}

	/**
	 * accessor method for profile name
	 * 
	 * @return string current value of profile name
	 **/
	public function getProfileName(): string {
		return($this->profileName);
	}

	/**
	 * mutator method for profile name
	 * 
	 * @param string $newProfileName new value of profile name
	 * @throws \InvalidArgumentException if profile name is empty
	 * @throws \RangeException if profile name is too large
	 **/
	public function setProfileName(string $newProfileName) {
		$newProfileName = trim($newProfileName);
		$newProfileName = filter_var($newProfileName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileName) === true) {
			throw(new \InvalidArgumentException("profile name cannot be empty"));
		}

		if(strlen($newProfileName) > 18) {
			throw(new \RangeException("profile name is too large"));
		}
		
		$this->profileName = $newProfileName;
	}

	/**
	 * accessor method for profile service
	 *
	 * @return string current value of profile service
	 **/
	public function getProfileService(): string {
		return $this->profileService;
	}

	/**
	 * mutator method for profile service
	 *
	 * @param string $newProfileService new value of profile service
	 **/
	public function setProfileService(string $newProfileService) {
		$validServices = ["T"]; // Twitter only for now
		$newProfileService = trim(strtoupper($newProfileService));
		if(in_array($newProfileService, $validServices) === false) {
			throw(new \InvalidArgumentException("invalid profile service"));
		}

		$this->profileService = $newProfileService;
	}


}
