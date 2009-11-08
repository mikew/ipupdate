<?php

// $pool will be an associative array where the keys are the name of the
// runner (currently, one of CJB or ZoneEdit) pointing to an array of the username,
// the password and the optional array of parameters to be passed to the runner object
// 
// Example
// 
//   $pool = array(
//     'cjb' => array('username', 'password'),
//     'zoneedit' => array('username', 'password', array('host' => 'yourdomain.com'))
//   );
