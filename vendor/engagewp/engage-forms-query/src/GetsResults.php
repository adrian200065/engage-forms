<?php


namespace engagewp\EngageFormsQuery;

interface GetsResults
{
	/**
	 * @param $sql
	 * @return \stdClass[]
	 */
	public function getResults($sql);
}
