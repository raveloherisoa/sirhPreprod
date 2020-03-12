<?php 
	
	/** 
	 * Entité EntreprisePoste
	 *
	 * @author Voahirana 
	 *
	 * @since 12/03/20
	 */

	namespace Entity;

	class EntreprisePoste
	{
		private $idEntreprisePoste;
		private $idEntreprise;
		private $poste;
		
		/** 
		 * Initialisation d'un EntreprisePoste
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
		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getPoste()
		{
			return $this->poste;
		}

	// Seters
		public function setIdEntreprisePoste($idEntreprisePoste)
		{
			$this->idEntreprisePoste = $idEntreprisePoste;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setPoste($poste)
		{
			$this->poste = $poste;
		}
		
	}