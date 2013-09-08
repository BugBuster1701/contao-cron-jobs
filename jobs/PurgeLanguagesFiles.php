<?php 

/**
 * Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 *
 * Job for Contao Module "Cron Scheduler"
 * PHP script to execute by cron: Purges superfluous languages files
 * in system/modules/core/languages/ , not in any extensions
 * The script does not change the files countries.php and languages.php!
 * 
 * NOTES
 * After running this script, Contao check will be unhappy.
 * After updating Contao, run this script again.
 * 
 * Job: system/modules/cron_jobs_bugbuster/jobs/PurgeLanguagesFiles.php
 *
 * @copyright  Glen Langer 2013 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @package    Cron_Job
 * @license    LGPL
 * @filesource
 * @see	       https://github.com/BugBuster1701/contao-cron
 */

/**
 * Initialize the system if necessary
 */
if (!defined('TL_MODE')) 
{
    define('TL_MODE', 'BE');
    require_once('../../../initialize.php');
}


/**
 * Class PurgeLanguagesFiles
 * 
 * @copyright  Glen Langer 2013 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @package    Cron_Job
 */
class PurgeLanguagesFiles extends Controller //Backend
{
    // define the languages you want to keep here, do not kill "." and ".."!
    protected $languages = array('.','..'
                                ,'de','en','fr','ja');
    // define the language dir
    protected $languages_dir  = 'system/modules/core/languages'; 

    /**
     * Initialize the controller
     */
    public function __construct()
    {
    	parent::__construct();
    } // __construct
	
    /**
     * Implement the commands to run by this batch program
     */
    public function run()
    {
        global  $cronJob; // from CronController
    
        //At this time the job should be defered, 
        //no new actions should be started after this time.
        if (time() >= $cronJob['endtime'])
        {
            $cronJob['completed'] = false;
            return;
        }
        
        foreach ( array_diff( scandir( TL_ROOT . '/' . $this->languages_dir ), $this->languages ) as $dir )
        {
            if (is_dir( TL_ROOT . '/' . $this->languages_dir . '/' . $dir ))
            {
                $objDir = new Folder( $this->languages_dir . '/' . $dir );
                $objDir->delete(); //intern: rrdir inkl. $this->dir
                $objDir = null;
                unset($objDir);
                $this->killed = true;
            }
        }    
        if ($this->killed && $cronJob['logging'])
        {
            $this->log('Superfluous languages files purged by cron job.', 'PurgeLanguagesFiles run()', TL_GENERAL);
            // purge the internal cache
            // system/cache/dca, system/cache/sql, system/cache/language
            $this->import('Automator');
            $this->Automator->purgeInternalCache();
        }
    } // run
	
} // class PurgeLanguagesFiles

/**
 * Instantiate log purger
 */
$objPurge = new PurgeLanguagesFiles();
$objPurge->run();

