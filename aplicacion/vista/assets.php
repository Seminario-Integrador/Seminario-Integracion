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
		

		/**
		*	Constructor de Assets - clase utilitaria
		*/
		function __construct()
		{
			$this->select = '<select name="{{nombre}}">{{valores}}</select>';
			$this->optionSelect = '<option value="{{valor}}">{{valor}}</option>';
		}


		/**
		*	Método que devuelve el codigo html de un select para insertarlo en la vista
		*	@return string con el html del select
		*/
		public function getSelect(){
			return $this->select;
		}


		/**
		*	Método que devuelve el codigo html de un option para insertarlo en la vista
		*	@return string con el html del option
		*/
		public function getOptionSelect()
		{
			return $this->optionSelect;
		}
	}
	
?>