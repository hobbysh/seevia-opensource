<?php
    /**
     * $seevia$
     * $Id$.
     */
    include_once dirname(ROOT).'/data/config.php';

    if (isset($_GET['debug'])) {
        Configure::write('debug', $_GET['debug']);
    } else {
        Configure::write('debug', debug);
    }
    Configure::write('App.encoding', 'UTF-8');
    Configure::write('Cache.disable', true);
    define('LOG_ERROR', 2);

    define('DateTime', date('Y-m-d H:i:s'));
    define('Today', date('Y-m-d'));
    define('StartTime', '00:00:00');
    define('EndTime', '23:59:59');

    Configure::write('Security.salt', 'a1b9f79d12e5d1f3db8393165155b839');
    Configure::write('Session.save', 'php');
    Configure::write('Session.cookie', 'IOCOADMIN');
    Configure::write('Session.timeout', '600');
    Configure::write('Session.start', true);
    Configure::write('Session.checkAgent', true);
    Configure::write('Security.level', 'medium');

    Cache::config('default', array('engine' => 'File'));

    Configure::write('themes_host', '/admin');

    if (!defined('COMBINATOR_PATH')) {
        define('COMBINATOR_PATH', 'data/admin/');
    }
