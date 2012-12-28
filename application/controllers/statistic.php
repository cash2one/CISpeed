<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Statistic extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( "user_model" );
		$this->load->model ( "game_model" );
		$this->load->model ( "speed_model" );
		$this->load->model ( "node_model" );
	}
	
	function index() {
	}
	
	function getUserLose() {
		$this->user_model->getUserLose ();
	}
	
	function getUserLoseVersion1() {
		$this->user_model->getUserLoseVersion1 ();
	}
	
	function getUserLoseVersion2() {
		$this->user_model->getUserLoseVersion2 ();
	}
	
	function getNewUserRetain() {
		$this->user_model->getNewUserRetain ();
	}
	
	function getGameSpeeding() {
		$this->game_model->getGameSpeeding ();
	}
	
	function getGameSpeedingByNode() {
		$this->game_model->getGameSpeedingByNode ();
	}
	
	function getGameSpeedingProxy() {
		$this->game_model->getGameSpeedingProxy ();
	}
	
	function getGameSpeedingByNodeProxy() {
		$this->game_model->getGameSpeedingByNodeProxy ();
	}
	
	function getSpeedData() {
		$this->speed_model->generateSpeedData ();
	}
	
	function getMachineCodeData() {
		$this->user_model->getMachineCodeData ();
	}
	
	function getUserTimeData() {
		$this->user_model->getUserTimeData ();
	}
	
	function getGameNodeDataPerDay() {
		$this->node_model->getGameNodeDataPerDay(date("Y-m-d",time()-24*60*60));
	}
	
	function getNodePeakData()
	{		
		//$this->speed_model->getNodesPeakCount(date("Y-m-d",strtotime($this->input->get("d"))));
		$this->speed_model->getNodesPeakCount(date("Y-m-d",time()-24*60*60));
	}
	
	
	function fix() {
		$this->game_model->fix ( $this->input->get ( "date" ) );
	}
}
?>