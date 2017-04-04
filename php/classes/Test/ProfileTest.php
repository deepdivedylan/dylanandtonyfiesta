<?php
namespace Deepdivedylan\DylanAndTonyFiesta\Test;

use Deepdivedylan\DylanAndTonyFiesta\Profile;

require_once(dirname(__DIR__) . "/autoload.php");

class ProfileTest extends DylanAndTonyFiestaTest {
	/**
	 * valid profile id to use
	 * @var string $VALID_PROFILEID
	 **/
	protected $VALID_PROFILEID = "847297430630522880";
	/**
	 * valid profile name to use
	 * @var string $VALID_PROFILENAME
	 **/
	protected $VALID_PROFILENAME = "SenatorArlo";
	/**
	 * valid profile service to use
	 * @var string $VALID_PROFILESERVICE
	 **/
	protected $VALID_PROFILESERVICE = "T";

	/**
	 * test inserting valid profile
	 **/
	public function testInsertValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		$profile = new Profile($this->VALID_PROFILEID, $this->VALID_PROFILENAME, $this->VALID_PROFILESERVICE);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileName(), $profile->getProfileName());
		$this->assertEquals($pdoProfile->getProfileService(), $profile->getProfileService());
	}

	/**
	 * test grabbing all profiles
	 **/
	public function testGetAllProfiles() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		$profile = new Profile($this->VALID_PROFILEID, $this->VALID_PROFILENAME, $this->VALID_PROFILESERVICE);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$profiles = Profile::getAllProfiles($this->getPDO());
		$this->assertCount(1, $profiles);
		$this->assertContainsOnlyInstancesOf("Deepdivedylan\\DylanAndTonyFiesta\\Profile", $profiles);

		$pdoProfile = $profiles[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profile->getProfileId());
		$this->assertEquals($pdoProfile->getProfileName(), $profile->getProfileName());
		$this->assertEquals($pdoProfile->getProfileService(), $profile->getProfileService());
	}
}