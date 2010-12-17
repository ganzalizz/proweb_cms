<?php
// считываем текущее время
$start_time = microtime();
// разделяем секунды и миллисекунды
//(становятся значениями начальных ключей массива-списка)
$start_array = explode(" ",$start_time);
// это и есть стартовое время
$start_time = $start_array[1] + $start_array[0];
?>