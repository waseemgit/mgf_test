FOR PHP 5.3 PLUS PHPUNIT TESTING INCLUDED
=========================================

-Put the directory in root server directory.
-Go to includes/config.php to change database settings.
-Make database name with name dvo_test and import following script into that

CREATE TABLE IF NOT EXISTS `dvo_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL COMMENT '1=deleted,0=not deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `dvo_users`
--

INSERT INTO `dvo_users` (`id`, `username`, `password`, `created_by`, `updated_by`, `date_created`, `date_updated`, `deleted`) VALUES
(1, 'waseem', '098f6bcd4621d373cade4e832627b4f6', 1, 1, '2015-12-17 00:00:00', '2015-12-18 03:03:53', 0);


-Run it like this http://localhost/dvo_test1 
-Username: waseem
-password: test

TO RUN PHP UNIT TEST
====================
Open Terminal and write following command

cd /var/www/html/dvo_test1/vendor/bin

root directory in my case was dvo_test1 you can rename it and rename in command also.

then write following command to run PHPUNIT Tests

./phpunit /var/www/html/dvo_test1/Application/Tests/usersTest.php


