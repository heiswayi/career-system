DROP TABLE IF EXISTS `jobseeker`;
CREATE TABLE IF NOT EXISTS `jobseeker` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY auto_increment,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `ic_no` varchar(14) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `is_usm` tinyint(1) unsigned NOT NULL,
  `study_field` varchar(100) NOT NULL,
  `is_undergrad` varchar(1) NOT NULL default '2',
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postcode` varchar(25) NOT NULL,
  `state` varchar(140) NOT NULL,
  `reg_date` int(10) unsigned NOT NULL,
  `has_resume` tinyint(1) unsigned NOT NULL,
  `resume_path` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL
);

DROP TABLE IF EXISTS `apply_job`;
CREATE TABLE IF NOT EXISTS `apply_job` (
    `id` int(10) unsigned NOT NULL PRIMARY KEY auto_increment,
    `job_id` int(10) unsigned NOT NULL,
    `jobseeker_id` int(10) unsigned NOT NULL,
    `accept_status` varchar(1) NOT NULL default '2'
);

DROP TABLE IF EXISTS `apply_interview`;
CREATE TABLE IF NOT EXISTS `apply_interview` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY auto_increment,
  `employer_id` int(10) unsigned NOT NULL,
  `jobseeker_id` int(10) unsigned NOT NULL,
  `accept_status` varchar(1) NOT NULL default '2'
);

DROP TABLE IF EXISTS `employer`;
CREATE TABLE IF NOT EXISTS `employer` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY auto_increment,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `person_name` varchar(75) NOT NULL,
  `department` varchar(140) NOT NULL,
  `company_name` varchar(140) NOT NULL,
  `person_prefix` varchar(5) NOT NULL,
  `company_logo_url` varchar(255) NOT NULL,
  `company_website` varchar(255) NOT NULL,
  `company_info` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `is_sponsor` tinyint(1) unsigned NOT NULL default '0',
  `sponsor_type` varchar(50) NOT NULL,
  `is_available` tinyint(1) unsigned NOT NULL default '1',
  `company_tag` varchar(255) NOT NULL
);

DROP TABLE IF EXISTS `job_vacancy`;
CREATE TABLE IF NOT EXISTS `job_vacancy` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY auto_increment,
  `title` varchar(140) NOT NULL,
  `location` varchar(140) NOT NULL,
  `special` varchar(255) NOT NULL,
  `brief` text NOT NULL,
  `details` text NOT NULL,
  `requirement` text NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `posted_date` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `is_open` tinyint(1) unsigned NOT NULL default '1'
);

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY auto_increment,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL
);

INSERT INTO `admin` (`username`, `password`, `fullname`) VALUES ('csadmin', '9cc76d014eb747e0b537076779ceb1c1', 'DEFAULT ACCOUNT');

DROP TABLE IF EXISTS `request_loginid`;
CREATE TABLE IF NOT EXISTS `request_loginid` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY auto_increment,
  `email` varchar(255) NOT NULL,
  `comp_name` varchar(255) NOT NULL,
  `person_name` varchar(255) NOT NULL,
  `status` varchar(1) NOT NULL default '0'
);
