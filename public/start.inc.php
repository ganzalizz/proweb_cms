<?php
// ��������� ������� �����
$start_time = microtime();
// ��������� ������� � ������������
//(���������� ���������� ��������� ������ �������-������)
$start_array = explode(" ",$start_time);
// ��� � ���� ��������� �����
$start_time = $start_array[1] + $start_array[0];
?>