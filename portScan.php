<?php
    require_once './lib/functions.php';

    $result = '';
    foreach(getOpenedPorts() as $port) {
        $result .= $port['port'] . ' (' . $port['service'] . ') открыт<br>';
    }
    echo empty($result) ? 'Не обнаружено' : $result;
