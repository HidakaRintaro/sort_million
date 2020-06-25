<?php
require './fnc.php';

ini_set("memory_limit", "1G");
set_time_limit(0);

$start = hrtime(true);

// ファイル読込
// $list = file_read('kana.csv');
// $list = file_read('test.csv');
$list = file_read('TestData.csv');
$a1 = hrtime(true);

// --------------------------
// ソート
// --------------------------
$cnt = 0;
while ($cnt < 10) {
  $left = $cnt * 100000;
  $right = 100000 * ($cnt + 1) - 1;
  $data_list = q_sort_a($list, $left, $right, 2);
  $cnt++;
}
// (配列,　前半開始位置,　前半件数,　後半開始位置,　後半件数,　ソートカラム)
$data_list = merge($data_list, 0, 100000, 100000, 100000, 2);
$data_list = merge($data_list, 200000, 100000, 300000, 100000, 2);
$data_list = merge($data_list, 400000, 100000, 500000, 100000, 2);
$data_list = merge($data_list, 600000, 100000, 700000, 100000, 2);
$data_list = merge($data_list, 800000, 100000, 900000, 100000, 2);
$data_list = merge($data_list, 0, 200000, 200000, 200000, 2);
$data_list = merge($data_list, 400000, 200000, 600000, 200000, 2);
$data_list = merge($data_list, 400000, 400000, 800000, 200000, 2);
$data_list = merge($data_list, 0, 400000, 400000, 600000, 2);

// $data_list = mergeSort($list,0,count($list)-1);
// $data_list = m_sort_a($list,0,count($list)-1);
// $left = 0;
// $right = count($list) - 1;
// // $right = 1000 - 1;
// $data_list = quick_sort($list, $left, $right);

// $data_list = $list;
// sort($data_list);

$a2 = hrtime(true);

// // 2次元
// $fp = fopen('log1.csv', 'a');
// foreach ($data_list as $row) {
//   fputs($fp, implode(',', $row)."\n");
// }
// fclose($fp);

// // 1次元
// $fp = fopen('log1.csv', 'a');
// foreach ($data_list as $row) {
//   fputs($fp, $row."\n");
// }
// fclose($fp);

echo '読込時間:'.(($a1 - $start) / 1000000000).'秒<br>';
echo 'ソート時間:'.((($a2 - $start) / 1000000000)-(($a1 - $start) / 1000000000)).'秒<br>';
echo '全体時間:'.(($a2 - $start) / 1000000000).'秒<br>';