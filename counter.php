<?php

$path = 'counter_log.txt';

// Read
$file  = fopen( $path, 'r' );
$count = fgets( $file, 1000 );
fclose( $file );

// Update
$count = abs( intval( $count ) ) + 1;

// Write
$file = fopen( $path, 'w' );
fwrite( $file, $count );
fclose( $file );
