<?php

	/**
	* 
	*/
	class Assets
	{
		private $select;
		private $optionSelect;
		
		function __construct()
		{
			$this->select = '<select name="{{nombre}}">{{valores}}</select>';
			$this->optionSelect = '<option value="{{valor}}">{{valor}}</option>';
		}
		public function getSelect(){
			return $this->select;
		}
		public function getOptionSelect()
		{
			return $this->optionSelect;
		}
	}
	
?>