#!/bin/bash
./phpunit --bootstrap autoloader.php UserTest
./phpunit --bootstrap autoloader.php AssignmentTest
./phpunit --bootstrap autoloader.php CheckTest
