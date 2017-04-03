<?php

namespace Deepdivedylan\DylanAndTonyFiesta;

/**
 * Profile Class
 *
 * Generic profile container for authors of social media
 *
 * @package Deepdivedylan\Dylanandtonyfiesta
 **/
class Profile implements \JsonSerializable {
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
	 * @param string $newProfileId new value of profile id
	 * @param string $newProfileName new value of profile name
	 * @param string $newProfileService new value of profile service
	 * @throws \InvalidArgumentException if values are empty or insecure
	 * @throws \RangeException if values are too large
	 * @throws \TypeError if type declarations fail
	 **/
	public function __construct(string $newProfileId, string $newProfileName, string $newProfileService) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileName($newProfileName);
			$this->setProfileService($newProfileService);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
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

	/**
	 * inserts this Profile into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// create query template
		$query = "INSERT INTO profile(profileId, profileName, profileService) VALUES(:profileId, :profileName, :profileService)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["profileId" => $this->profileId, "profileName" => $this->profileName, "profileService" => $this->profileService];
		$statement->execute($parameters);
	}

	/**
	 * gets the Profile by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileId profile id to search for
	 * @return Profile|null Profile found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileByProfileId(\PDO $pdo, string $profileId) : ?Profile {
		// sanitize the profile id before searching
		$profileId = trim($profileId);
		$profileId = filter_var($profileId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($profileId) === true) {
			throw(new \PDOException("profile id is invalid"));
		}

		// create query template
		$query = "SELECT profileId, profileName, profileService FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the profile id to the place holder in the template
		$parameters = ["profileId" => $profileId];
		$statement->execute($parameters);

		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileName"], $row["profileService"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		return($profile);
	}

	/**
	 * gets all Profiles
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray all Profiles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllProfiles(\PDO $pdo) : \SplFixedArray {
		// create query template
		$query = "SELECT profileId, profileName, profileService FROM profile";
		$statement = $pdo->prepare($query);
		$statement->execute();

		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileName"], $row["profileService"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
	}


	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);
		return($fields);
	}
}