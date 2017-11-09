<?php

    /**
     * @global JWT_BASE ; The base path of this plugin.
     */
    define('JWT_BASE', __DIR__);

    /**
     * @uses JWT            Neuman Vong
     * @uses TokenModel     Alexander Ljungberg Perme
     */
    require_once JWT_BASE.'/lib/jwt/JWT.php';
    require_once JWT_BASE.'/lib/jwt_model.php';

    /**
     * Handles registering, verifying and removing jwt strings.
     * 
     * @category Verification
     * @package jwt_store
     * @version 1.0.0
     * 
     * @copyright 2017 MIT
     * @author Alexander Ljungberg Perme <alex.perme@gmail.com>
     */
    class TokenStore extends TokenModel {
        
        static private $config;

        /**
         * Initiating function of this class, gets the configuration if any.
         */
        public function __construct () {
            //  Read the config settings from json file.
            $raw = file_get_contents(JWT_BASE.'/etc/jwt.conf.json');
            $conf_obj = null;
            try { $conf_obj = json_decode($raw); }
            catch (Exception $e) { $conf_obj = false; }
            //  Use read setting if exists or use default.
            if (!$conf_obj || !is_object($conf_obj)) {
                self::$config = (object)[
                    'key_length' => 64,
                    'valid_for' => 1800
                ];
            } else {
                self::$config = $conf_obj;
                if (!property_exists(self::$config, 'key_length')) {
                    self::$config->key_length = 64;
                } else if (!property_exists(self::$config, 'valid_for')) {
                    self::$config->valid_for = 1800;
                }
            }
        }

        /**
         * Creates a new jwt and registers it in the database.
         *
         * @param integer $id
         * @param object $data
         * 
         * @return string|boolean
         */
        public function create ($id, $data) {
            //  Confirm valid parameters.
            if (!is_int($id) || !is_object($data)) { return false; }
            //  Create the jwt and save it.
            $salt = self::genSalt();
            $token = $salt ? JWT::encode($data, $salt) : false;
            $is_saved = $token ? parent::insert($id, $token, $salt) : false;
            //  Return result.
            return !$is_saved ? $is_saved : $token;
        }

        /**
         * Verifies the given token as a registed jwt, and updates it.
         *  
         * @param string $token
         * @param object $new_data (optional)
         * 
         * @return string|boolean
         */
        public function verify ($token, $new_data = null) {
            //  Verify the parameters.
            if (!is_string($token) || (!is_null($new_data) && !is_object($new_data))) { return false; }
            //  Get the token information and verify it.
            $info = parent::select($token);
            $is_expired = ($info->unix + self::$config->valid_for) > getTime() ? false : true;
            if ($is_expired) { 
                self::destroy($token); 
                return false; 
            }
            $data = null;
            try { $data = JWT::decode($token, $info->salt); }
            catch (Exception $e) { return false; }
            //  Create a new token with new salt.
            $new_salt = self::genSalt();
            $new_token = is_object($new_data) ? JWT::encode($new_data, $new_salt) : JWT::encode($data, $new_salt);
            $is_saved = parent::update($info->id, $new_token, $new_salt);
            //  Return the result.
            return $is_saved ? $new_token : false;
        }

        /**
         * Unregisters the given token from the database.
         *
         * @param string $token
         * 
         * @return boolean
         */
        public function destroy ($token) {
            //  Verify the parameter.
            if (!is_string($token)) { return false; }
            //  Get the id of the token and delete it if it exists.
            $info = parent::select($token);
            if (!$info) { return true; }
            return parent::delete($info->id);
        }

        /**
         * Genereates a salt to encrypt the jwt with.
         * 
         * @return string
         */
        private function genSalt () {
            if (self::$config->key_length < 0) { return false; }
            //  Create a string of characters.
            $nums = '01234567890123456789';
            for ($i = 0; $i < 6; $i++) { $nums.= rand(0, 9); }
            $sm_chars = 'abcdefghijklmnopqrstuvwxyz';
            $lg_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $chars = str_shuffle($nums.$sm_chars.$lg_chars);
            $chars_len = strlen($chars);
            $rand_str = '';
            //  Randomly select chars from string.
            for ($i = 0; $i < self::$config->key_length; $i++)
            { $rand_str.= $chars[rand(0, $chars_len-1)]; }
            //  Return string.
            return $rand_str;
        }

    }
?>