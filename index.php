<?php
    require_once './lib/functions.php';

    $ip = getIP();

    // Если запрос через curl - отдадим только строку с IP адресом
    if (isCurl()) {
        die($ip . PHP_EOL);
    }
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
        <meta name="viewport" content="width=device-width, initial-scale=0.75, user-scalable=no">
        <meta charset="utf-8">
		<title>Ваш IP-адрес. Узнать IP адрес, определить IP-адрес, определить свой IP</title>
		<meta name="description" content="Здесь Вы можете узнать свой IP-адрес. Чтобы определить IP адрес достаточно зайти на сайт ip.it-stories.ru.">
		<meta name="keywords" content="узнать IP-адрес, определить IP адрес, проверить интернет адрес, определить свой IP">
			<style>
				* { padding:0; margin:0; }
				@keyframes blink {
					0% { opacity: 1; }
					50% { opacity: 0; }
					100% { opacity: 1; }
				   }
				body { background: linear-gradient(117deg, #e6ffce, #fff5b1); }
				table { border-collapse:collapse; width:100%;     box-shadow: 0px 0px 10px 0px #9f9f9f;}
				a { color:#888888; }
                .container { position:relative; width:500px; margin-left:-250px; left:50%; top:30px; height:350px; }
                table.network-info td, table.network-info th { border:solid 1px #43AA2E; padding:5px; text-align:center; }
                table.network-info.geo-data td, table.network-info.geo-data th { border:solid 1px #cacaca; }
				table.network-info th { background-color:#E0EED3; }
                table.network-info.geo-data th { background-color:#e2e2e2;    color: #505050; }
				table.network-info td { background-color:#FFFFFF; }
                table.network-info.geo-data td {
                    color: #737373;
                }
				.copy { padding:5px 10px; font-size:14px; color:#888888; }
				.migalka {
					animation: blink 1s infinite;
					background: red;
                    width: 10px;
                    height: 10px;
					display: inline-block;
					border-radius: 45px;
				}

                .ip-scan-button {
                    background: #e0eed3;
                    color: green;
                    border: 1px solid #43aa2e;
                    cursor: pointer;
                    padding: 4px;
                    width: 100%;
                    transition: 0.2s;
                }
                .ip-scan-button:hover {
                    background: #cdf1ac;
                    transition: 0.2s;
                }
                .ipField {
                    font-size: 3.5em;
                    font-family: monospace;
                    font-weight: bold;
                    color: #5ba814;
                }
            </style>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	</head>
	<body>
		<div class="container">
		<div id="ipcontent">
            <table class="network-info">
                <tr><th><div class="migalka"></div> Ваш IP-адрес</th></tr>
                <tr><td class="ipField"><?= $ip ?></td></tr>
            </table>
            <br>
            <?php $geoData = getIpData(); ?>
            <table class="network-info geo-data">
                <tr><th colspan="2">Дополнительная информация</th></tr>
                <tr><td><b>Имя хоста</b></td><td><?= gethostbyaddr(getIP()) ?></td></tr>
                <tr><td><b>Страна</b></td><td><?= $geoData['country']['name_ru'] ?></td></tr>
                <tr><td><b>Регион</b></td><td><?= $geoData['region']['name_ru'] ?></td></tr>
                <tr><td><b>Город</b></td><td><?= $geoData['city']['name_ru'] ?></td></tr>
                <tr><td><b>Координаты</b></td><td><a target="_blank" href="https://yandex.ru/maps/?text=<?= $geoData['city']['lat'] ?>,<?= $geoData['city']['lon'] ?>"><?= $geoData['city']['lat'] ?>, <?= $geoData['city']['lon'] ?></a></td></tr>

                <tr>
                    <td><b>Открытые порты</b></td>
                    <td id="scanResult">
                        <button class="ip-scan-button" id="startScan">Сканировать</button>
                    </td></tr>
            </table>

		</div>
			<div class="copy">
				<a href="https://it-stories.ru/">it-stories.ru</a>&nbsp;<sup>&copy;</sup>&nbsp;2023 - <?= date('Y'); ?>
			</div>
</div>
<script>
    $(document).ready(
        function(){
            $('#startScan').on('click', function(){
                $('#scanResult').html('Идет сканирование...');
                $.ajax({
                    url: '/portScan.php',
                    method: 'get',
                    dataType: 'html',
                    data: {ip: '<?= $ip ?>'},
                    success: function (data) {
                        $('#scanResult').html(data);
                    },
                    error: function () {
                        $('#scanResult').html('Произошла ошибка :(');
                    },
                });
            });
        }
    );
</script>
</body>
</html>
