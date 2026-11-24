<?php
/*
 * settings.php - database connection details, used by every page that talks to MySQL.
 *
 * The brief says not to set a password on the local server, so $pwd is left empty.
 * If your server uses a different user or database name (the lab server often gives you
 * your student ID for both), change the values below and nothing else needs touching.
 */
$host   = "localhost";
$user   = "root";
$pwd    = "";            // intentionally empty for the local server
$sql_db = "g05_retail";  // created automatically the first time a page loads (see functions.php)
