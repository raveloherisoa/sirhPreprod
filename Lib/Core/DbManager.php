<?php

    /**
     * Connexion à la base de données
     *
     * @author Voahirana
     *
     * @since 30/09/19
     */

	namespace Core;

	use \PDO;

	abstract class DbManager
	{
        /**
         * Se connecter à la base
         *
         * @return empty
         */
        public function pdo() 
        {
	    	try {
	    		 return new PDO("mysql:host=localhost;dbname=dbsirhpreprods187920com;char=utf-8", "sirhprs187920com", "xbCWwfWh");
	    	} catch (PDOException $e) {
	    		echo $e->getMessage;
	    	}
	    }

        /**
         * Lister les données d'une table
         *
         * @param string $table Nom d'une table
         * @param string $attributes les conditions de la requête
         * @param string $string la suite de la requête
         *
         * @return array 
         */
    	public function findAll($table, $attributes = null, $string = null) 
    	{
    		$db    = $this->pdo();
            $str   = "";
            if (empty($attributes)) {
                $query = "SELECT * FROM ". $table;
            } else {
                foreach ($attributes as $key => $value) {
                    if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                        $value = $value;
                    } else {
                        $value =  "'" . $value . "'";
                    } 
                    $str .= $key . " = " . $value . " AND ";
                }
                $conditions = substr($str, 0, -5);
                $query      = "SELECT * FROM ". $table . " WHERE " . $conditions;
            }
            $requete = $db->prepare($query . $string);
            $requete->execute();
            return $requete->fetchAll();
    	}

        /**
         * insérer une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param array $attributes les données à insérer
         *
         * @return empty 
         */
        public function insert($table, $attributes)
        {
            $db    = $this->pdo();
            $query = 'INSERT INTO '. $table .' (';
            foreach ($attributes as $key => $value) {
                $query .= '`' . $key . '`, '; 
            }
            $query  = substr($query, 0, -2);
            $query .= ') VALUES (';
            foreach ($attributes as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    // @Ravao: Ici, on n'a pas besoin d'appeler la méthode 'quote' car ici, $value est de type integer
                    $query .= $value . ", ";
                } else {
                    // @Ravao:  C'est ici qu'on aura besoin d'appeler la méthode 'quote' pour un string
                    //          Cette méthode est censé rajouter les caractères apostrophes avant et après 
                    //          la chaîne de caractère en plus de protéger la chaîne pour l'utiliser dans une requête SQL PDO; 
                    //          Ce qui a causé l'erreur c'est que nous avons encore mis deux apostrophes avant et après
                    //          Ça devrait être bon maintenant ;)  
                    $query .= $db->quote($value) . ", ";
                    //$query .=  "'" . $value . "', ";
                }                 
            }
            $query   = substr($query, 0, -2);
            $query  .= ')';
            $requete = $db->prepare($query);
            $requete->execute();
            return $db->lastInsertId();
        }

        /**
         * modifier une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param array $attributes les données à modifier
         *
         * @return empty 
         */
        public function update($table, $attributes)
        {
            $db    = $this->pdo();
            $query = "UPDATE ". $table ." SET ";
            foreach ($attributes as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $query .= $key . " = " . $value . ", "; 
                } else {
                    $query .= $key . " = " . $db->quote($value) . ", "; 
                }                
            }
            $query   = substr($query, 0, -2);
            $query  .= " WHERE " . key($attributes) . " = " . reset($attributes);
            $requete = $db->prepare($query);
            $requete->execute();
        }

        /**
         * Chercher une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param string $fields Champs spécifiques
         * @param string $values Données à chercher
         *
         * @return array 
         */
        public function findOne($table, $fields, $values) 
        {
            $str = "";
            $db  = $this->pdo();
            for ($i = 0; $i < count($fields); $i++) { 
                $str .= $fields[$i] . " = " . $values[$i] . " AND ";
            }
            $conditions = substr($str, 0, -5);
            $query      = "SELECT * FROM " . $table . " WHERE " . $conditions;
            $requete    = $db->prepare($query);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Chercher le dérinier identifiant
         *
         * @param string $table Nom d'une table
         * @param string $id Champs spécifiques
         *
         * @return array 
         */
        public function findLast($table, $id) 
        {
            $db      = $this->pdo();
            $query   = "SELECT MAX($id) AS id FROM " . $table;
            $requete = $db->prepare($query);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Supprimer une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param string $attributes Les attributs des données à supprimer
         *
         * @return empty 
         */
        public function delete($table, $attributes) 
        {
            $db    = $this->pdo();
            $query = "DELETE FROM ". $table ." WHERE ";
            foreach ($attributes as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $query .= $key . " = " . $value . ", AND "; 
                } else {
                    $query .= $key . " = '" . $value . "', AND "; 
                }                
            }
            $query   = substr($query, 0, -6);
            $requete = $db->prepare($query);
            $requete->execute();
        }

	}