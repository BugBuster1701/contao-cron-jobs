<?php 

/**
 * Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 *
 * Job for Contao Module "Cron Scheduler"
 * PHP script to execute by cron: Purges the Music Academy Demo Files
 * Job: system/modules/cron_jobs_bugbuster/jobs/PurgeDemoFiles.php
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
    
    $dir = __DIR__;

    while ($dir != '.' && $dir != '/' && !is_file($dir . '/system/initialize.php'))
    {
        $dir = dirname($dir);
    }
    
    if (!is_file($dir . '/system/initialize.php'))
    {
        echo 'Could not find initialize.php!';
        exit(1);
    }
    require($dir . '/system/initialize.php');
}

/**
 * Class PurgeDemoFiles
 * 
 * @copyright  Glen Langer 2013 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @package    Cron_Job
 */
class PurgeDemoFiles extends Controller //Backend
{
    // delete files
    protected $file = 'templates/music_academy.sql';
    // delete directories
    protected $dir  = 'files/music_academy'; 
    //killing my software with w... :-)
    protected $killed = false;

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
        if (is_file(TL_ROOT . '/' . $this->file))
        {
            $objFile = new File($this->file);
            $objFile->delete();
            $objFile->close();
            $objFile=null;
            unset($objFile);
            $this->killed = true;
        }
    
        if (is_dir(TL_ROOT . '/' . $this->dir))
        {
            $objDir = new Folder($this->dir);
            $objDir->delete(); //intern: rrdir inkl. $this->dir
            $objDir=null;
            unset($objDir);
            $this->killed = true;
        }
    
        if ($this->killed && $cronJob['logging'])
        {
    	    $this->log('MA Demo files purged by cron job.', 'PurgeDemoFiles run()', TL_GENERAL);
        }
    } // run
	
} // class PurgeDemoFiles

/**
 * Instantiate log purger
 */
$objPurge = new PurgeDemoFiles();
$objPurge->run();

