<?php

	/**
	* 
	*/
	class Assets
	{
		public $_select;
		public $_optionSelect;
		
		public function assets()
		{
			$_select = '<select name="{{nombre}}">
					{{valores}}
				</select>';
			$_optionSelect = '<option value="{{valor}}">{{valor}}</option>';
		}
	}
	
?>