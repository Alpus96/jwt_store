<?php

    /**
     * Table sql;
     *  CREATE TABLE TOKEN_STORE(
     *      'ID' INT NOT NULL AUTO_INCREMENT,
     *      'TOKEN' TEXT NOT NULL,
     *      'SALT' VARCHAR(3072) NOT NULL
     *      'UNIX' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     *  );
     */

    /**
     * @uses Mysql Alexadner Ljungberg Perme
     */
    require_once 'mysql.php';

    /**
     * The model class for handling jwt entries in the mysql database.
     * 
     * @category Verification
     * @package jwt_store
     * @subpackage jwt_model
     * @version 1.0.0
     * 
     * @author Alexander Ljungberg Perme <alex.perme@gmail.com>
     * @copyright 2017 Alexander Ljungberg Perme
     * @license MIT
     */
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
            //  Confirm valid arguments.
            if (!is_int($id) || !is_string($token) || !is_string($salt)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->insert)) {
                $query->bind_param('iss', $id, $token, $salt);
                $query->execute();
                $was_successful = $query->$affected_rows > 0 ? true : false;
                $query->close();
                $conn->close();
                //  Return the query result.
                return $was_successful;
            }
            //  Close the connection if a cuery could not be prepared.
            $conn->close();
            //  Return result as false.
            return false;
        }

        /**
         * Slects all information in the database related to a token.
         *
         * @param string $token
         * 
         * @return object
         */
        protected function select ($token) {
            //  Confirm valid argument passed.
            if (!is_string($token)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->select)) {
                $query->bind_param('s', $token);
                $query->execute();
                //  Get the result.
                $query->bind_result($id, $salt, $unix);
                $query->fetch();
                $data = (object)[
                    'id' => $id,
                    'salt' => $salt,
                    'unix' => $unix
                ];
                //  Return the result.
                return $data ? $data : false;
            }
            //  Close the connection if a cuery could not be prepared.
            $conn->close();
            //  Return result as false.
            return false;
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
            //  Confirm the parameters are valid.
            if (!is_int($id) || !is_string($token) || !is_string($salt)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->update)) {
                $query->bind_param('iss', $id, $token, $salt);
                $query->execute();
                $was_successful = $query->$affected_rows > 0 ? true : false;
                $query->close();
                $conn->close();
                //  Return the query result.
                return $was_successful;
            }
            //  Close the connection if a cuery could not be prepared.
            $conn->close();
            //  Return result as false.
            return false;
        }

        /**
         * Deletes a jwt and all related information from the database.
         *
         * @param integer $id
         * 
         * @return boolean
         */
        protected function delete ($id) {
            if (!is_int($id)) { return false; }
            $conn = parent::connect();
            if (!$conn) { return false; }
            if ($query = $conn->prepare(self::$query->delete)) {
                $query->bind_param('i', $id);
                $query->execute();
                $was_successful = $query->$affected_rows > 0 ? true : false;
                $query->close();
                $conn->close();
                //  Return the query result.
                return $was_successful;
            }
            //  Close the connection if a cuery could not be prepared.
            $conn->close();
            //  Return result as false.
            return false;
        }

    }

?>