<?php
						if(!defined('includeConnDetails'))
						{
							header('HTTP/1.0 403 Forbidden');
							die("It is forbidden to access this page directly");
						}
						else
						{
							$database['dbServer'] = 'localhost';
							$database['dbName'] = 'WebComic';
							$database['dbUser'] = 'root';
							$database['dbPassword'] = 'password';
						}
	
					?>