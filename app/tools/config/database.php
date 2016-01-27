<?php
	include(dirname(ROOT).DS."data/database.php");
	class DATABASE_CONFIG
    {
        public $default = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svsys_',
            'encoding' => 'UTF8',
        );

        public $cms = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svcms_',
            'encoding' => 'UTF8',
        );

        public $oms = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svoms_',
            'encoding' => 'UTF8',
        );

        public $drm = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svdrm_',
            'encoding' => 'UTF8',
        );

        public $edi = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svedi_',
            'encoding' => 'UTF8',
        );

        public $wms = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svwms_',
            'encoding' => 'UTF8',
        );
        public $sns = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svsns_',
            'encoding' => 'UTF8',
        );
        
          public $wbp = array(
            'driver' => 'mysqli',
            'persistent' => false,
            'host' => MYSQL_HOST,
            'login' => MYSQL_LOGIN,
            'password' => MYSQL_PASSWORD,
            'database' => MYSQL_DB,
            'prefix' => 'svwbp_',
            'encoding' => 'UTF8',
        );
    }
?>