<?php

require __DIR__. '/conf/system.php';
require PD_SOCKET_ROOT . '/conf/log.php';

system('tail -f '.PD_LOG_FILE);