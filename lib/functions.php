<?php
    require_once './lib/SxGeo.php';

    /**
     * Проверка на curl
     *
     * @return bool
     */
    function isCurl(): bool
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'],'curl') !== false;
    }

    /**
     * Получения IP адреса
     *
     * @return string
     */
    function getIP(): string
    {
        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
        ];
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = @trim(end(explode(',', $_SERVER[$key])));
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return 'undefined';
    }

    /**
     * Получение информации о геоданных IP адреса
     *
     * @return array
     */
    function getIpData(): array
    {
        $SxGeo = new SxGeo('./lib/SxGeoCity.dat');
        $ip = getIP();
        return $SxGeo->getCityFull($ip) ?: ['country' => ['name_ru' => 'undefined'], 'city' => ['name_ru' => 'undefined', 'lat' => 0, 'lon' => 0], 'region' => ['name_ru' => 'undefined']];
    }

    /**
     * Получение списка стандартных портов
     *
     * @return int[]
     */
    function getDefaultPortList(): array
    {
        return [
            7,
            9,
            20,
            21,
            22,
            23,
            25,
            53,
            67,
            68,
            69,
            80,
            110,
            123,
            143,
            161,
            443,
            993,
            995,
            3389,
            8080,
            33434,
        ];
    }

    /**
     * Получение открытых портов
     *
     * @param string|null $ip
     * @return array
     */
    function getOpenedPorts(?string $ip = null): array
    {
        if (empty($ip)) {
            $ip = getIP();
        }
        $result = [];
        $scanList = getDefaultPortList();
        foreach ($scanList as $port){
            $connection = @fsockopen(hostname: $ip, port: $port, timeout: 0.1); // 0.05
            if (is_resource($connection)){
                $result[] = [
                    'port'    => $port,
                    'service' => getservbyport($port, 'tcp')
                ];
                fclose($connection);
            }
        }
        return $result;
    }
