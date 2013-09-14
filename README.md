# Contao CRON-Scheduler Jobs

## About

Cron jobs by BugBuster for the Contao extension [Cron Scheduler](https://github.com/BugBuster1701/contao-cron)

## Installation
Use the Extension Repository or create the directory ```system/modules/cron_jobs_bugbuster/``` and copy the directory ```jobs``` into it.  

## Jobs
* Purges the Music Academy Demo files
  * Job path: ```system/modules/cron_jobs_bugbuster/jobs/PurgeDemoFiles.php```
* Purges the superfluous languages files in system/modules/core/languages/ , not in any extensions
  * Read the Notes in the job file. 
  * In line 46 you can define the languages you want to keep
  * Job path: ```system/modules/cron_jobs_bugbuster/jobs/PurgeLanguagesFiles.php```

