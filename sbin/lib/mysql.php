<?php

    /**
     * This class handels reading the database configuration file,
     * and connecting to the database.
     *
     * @category       Datastoreage
     * @package        dataSockets
     * @subpackage     mysql_database
     * @version        2.0.0
     * 
     * @author Alexander Ljungberg Perme <alex.perme@gmail.com>
     * @copyright 2017 Alexander Ljungberg Perme
     * @license MIT
     */

    class Mysql {

        //  Credentials and default charset configuration.
        static private $config;

        /**
         * Initiates this class by reading the configuration file and setting it as a class variable.
         */
        protected function __construct () {
            //  Get the database configuretion.
            $raw = file_get_contents(JWT_BASE.'/sbin/etc/db.conf.json');
            $config = null;
            try { $config = json_decode($raw); }
            catch (Exception $e) { 
                $config = false;
                return;
            }
            //  Confirm it contains valid properties.
            if (!property_exists($config, 'credentials')) { 
                $config = false;
                return;
            } else {
                $host = property_exists($config->credentials, 'host');
                $user = property_exists($config->credentials, 'user');
                $password = property_exists($config->credentials, 'pass');
                $database = property_exists($config->credentials, 'dbase');
                if (!$host || !$user || !$password || !$database) { 
                    $config = false;
                    return;
                }
            }
            //  Set default charset.
            if ($config) {
                $chatset = property_exists($config, 'credentials');
                if (!$charset) { $config->charset = 'utf8'; }
            }
            //  Set the class configuration.
            self::$config = $config;
        }

        /**
         * Uses the set configuration to connect to the database.
         *
         * @return object mysqli_connect
         */
        protected function connect () {
            //  Confirm the configuration is set.
            if (!self::$config) { return false; }
            //  Connect to the database using the loaded credentials.
            $conn = new mysqli (
                self::$config->credentials->host,
                self::$config->credentials->user,
                self::$config->credentials->pass,
                self::$config->credentials->dbase
            );
            //  Confirm there was no error connecting to the database.
            if ($conn->connect_error) { return false; }
            //  Change the connection charset and save result as boolean.
            $charset = $conn->set_charset(self::$config->charset);
            //  If all is ok, return the connection.
            return $conn;
        }

    }
 ?>