<?php
// ������ �� ��, ��� � � start.php, ������ ���������� ������ ����������
$end_time = microtime();
$end_array = explode(" ",$end_time);
$end_time = $end_array[1] + $end_array[0];
// �������� �� ��������� ������� ���������
$time = $end_time - $start_time;
// ������� � �������� ����� (�������) ����� ��������� ��������
printf("<!--page generated on %f seconds-->",$time);
?>
