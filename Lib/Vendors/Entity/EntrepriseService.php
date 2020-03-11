<?php 
	
	/** 
	 * Entité EntrepriseService
	 *
	 * @author Voahirana 
	 *
	 * @since 11/03/20
	 */

	namespace Entity;

	class EntrepriseService
	{
		private $idEntrepriseService;
		private $idEntreprise;
		private $idService;
		
		/** 
		 * Initialisation d'un EntrepriseService
		 *
		 * @param array $data Données à intialiser 
		 *
		 * @return empty
		 */
	    public function __construct($data = array())
	    {
	        if(!empty($data)) {
	        	$this->hydrate($data);
	        }	            
	    }

	    /** 
		 * Remplir la structure d'un objet 
		 *
		 * @param array $data Données à remplir
		 *
		 * @return empty
		 */
	    public function hydrate($data)
	    {
	        foreach ($data as $attribut => $data) {
	            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
	            if (is_callable(array($this, $method))) {
	                $this->$method($data);
	            }
	        }
	    }

	    /** 
	     * Convertir un objet en tableau 
	     *
	     * @return array
	     */
	    public function toArray()
	    {
	    	return get_object_vars($this);
	    }

	// Getters
		public function getIdEntrepriseService()
		{
			return $this->idEntrepriseService;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdService()
		{
			return $this->idService;
		}

	// Seters
		public function setIdEntrepriseService($idEntrepriseService)
		{
			$this->idEntrepriseService = $idEntrepriseService;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setIdService($idService)
		{
			$this->idService = $idService;
		}
		
	}