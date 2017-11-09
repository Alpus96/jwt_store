<?php

    /**
     * Table sql;
     * 
     *  CREATE TABLE TOKEN_STORE(
     *      'ID' INT NOT NULL AUTO_INCREMENT,
     *      'TOKEN' TEXT NOT NULL,
     *      'SALT' VARCHAR(3072) NOT NULL
     *      'UNIX' TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     *  );
     */

    require_once JWT_BASE.'/mysql.php';

    class TokenModel extends Mysql {

        private static $query;

        function __construct () {
            parent::__construct();
            self::$query = (object)[
                'insert' => 'INSERT INTO TOKEN_STORE SET ID = ?, TOKEN = ?, SALT = ?',
                'select' => 'SELECT ID, SALT, UNIX FROM TOKEN_STORE WHERE TOKEN = ?',
                'update' => 'UPDATE TOKEN_STORE SET TOKEN = ?, SALT = ? WHERE ID = ?',
                'delete' => 'DELETE * FROM TOKEN_STORE WHERE ID = ?'
            ];
        }

        /**
         * Inserts a new jwt into the database.
         *
         * @param integer $id
         * @param string $token
         * @param string $salt
         * 
         * @return boolean
         */
        protected function insert ($id, $token, $salt) {

        }

        /**
         * Slects all information in the database related to a token.
         *
         * @param string $token
         * 
         * @return object
         */
        protected function select ($token) {

        }

        /**
         * Updates the token and it's salt in the database.
         *
         * @param integer $id
         * @param string $token
         * @param string $salt
         * 
         * @return boolean
         */
        protected function update ($id, $token, $salt) {

        }

        /**
         * Deletes a jwt and all related information from the database.
         *
         * @param integer $id
         * 
         * @return boolean
         */
        protected function delete ($id) {
            
        }

    }

?>