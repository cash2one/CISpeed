<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Statistic extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( "et/data_model", "etdata" );
	}
	
	function index() {
	}
	
	function getUserLose() {
		$this->etdata->getUserLose ();
	}
	
	function getUserLoginData() {
		$this->etdata->getUserLoginData ();
	}
	
	function getUserLose2point0() {
		$this->etdata->getUserLose2point0 ();
	}
	
	function getET2point0Data() {
		$this->etdata->getET2point0Data ();
	}
	
	function getXcbUserLoginData() {
		$this->etdata->getXcbUserLoginData ();
	}
	
	function getSingleHallData() {
		$this->etdata->getSingleHallData ();
	}
	
	function getUserLoginVersion() {
		$this->etdata->getUserLoginVersion ();
	}
	
	function getEtWebCountData() {
		$this->etdata->getEtWebCountData ();
	}
	
	function getActiveUser() {
		$this->etdata->getUserActiveData("2011-09-30",15,"2011-09");
	}
	
	function fix() {
		$this->etdata->fix ();
	}
}
?>