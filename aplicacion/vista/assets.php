<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/

	/**
	* Clase encargada de mantener la estructura de algunos de los elementos mas usados en el HTML. Recibe llamados desde el controlador.php
	* @author Gerson Yesid Lázaro Carrillo 1150972
	* @author Angie Melissa Delgado León 1150990
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