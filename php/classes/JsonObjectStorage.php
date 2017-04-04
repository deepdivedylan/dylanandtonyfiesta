<?php
namespace Deepdivedylan\DylanAndTonyFiesta;

/**
 * JsonObjectStorage Class
 *
 * This class adds JsonSerializable to SplObjectStorage, allowing for the stored data to be json serialized. This lets the data be gotten in the interactions between frontend and backend in the RESTful apis.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class JsonObjectStorage extends \SplObjectStorage implements \JsonSerializable {

	/**
	 * organizes the SplObjectStorage into associative JSON
	 *
	 * @return array organized fields to JSONify
	 **/
	public function jsonSerialize() : array {
		$fields = [];
		foreach($this as $object) {
			$fields[] = $object;
			$object->info = $this[$object];
		}
		return ($fields);
	}
}