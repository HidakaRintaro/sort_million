<?php
require './fnc.php';

ini_set("memory_limit", "1G");
set_time_limit(0);

print "実行開始[メモリ使用量]：". memory_get_usage() / (1024 * 1024) ."MB<br>";
print "実行開始[メモリ最大使用量]：". memory_get_peak_usage() / (1024 * 1024) ."MB<br>";
print "-----------------------------------------------<br>";
$start = hrtime(true);

// ファイル読込
// $list = file_read('kana.csv');
// $list = file_read('test.csv');
$list = file_read('TestData.csv');
$a1 = hrtime(true);

// --------------------------
// ソート
// --------------------------
$num_list = []; // [[ソート開始位置, ソート件数],・・・]
$split = 16; // 配列の分割数
$num = intval(count($list) / $split);
$rem = intval(count($list) % $split);
$total = count($list); // データの全体件数
for ($i=0; $i < $split; $i++) {
  $left = $i * $num;
  $right = $num * ($i + 1) - 1;
  if ($i < $split-1) {
    $data_list = q_sort_a($list, $left, $right, 2);
    $num_list[] = [$left, ( $right - $left + 1 )];
  } else {
    $data_list = q_sort_a($list, $left, $right+$rem, 2);
    $num_list[] = [$left, ( $right - $left + 1) + $rem];
  }
}

// マージの件数が全体の件数と一致するまで
for ($i=0; $total != $num_list[$i][1]; $i += 2) {
  // 前後位置を比較し、小さい方を前半の開始位置にする。
  if ( $num_list[$i][0] < $num_list[$i+1][0]) {
    $data_list = merge(
      $data_list,          // 配列
      $num_list[$i][0],    // 前半開始位置
      $num_list[$i][1],    // 前半件数
      $num_list[$i+1][0],  // 後半開始位置
      $num_list[$i+1][1],  // 後半件数
      2                    // ソート対象カラム
    );
  } else {
    $data_list = merge(
      $data_list,          // 配列
      $num_list[$i+1][0],  // 前半開始位置
      $num_list[$i+1][1],  // 前半件数
      $num_list[$i][0],    // 後半開始位置
      $num_list[$i][1],    // 後半件数
      2                    // ソート対象カラム
    );
  }
  $num_list[] = [
    $num_list[$i][0],
    $num_list[$i][1] + $num_list[$i+1][1]
  ];
}

// 同一値のチェック
$same_list = double_check($data_list);
echo '<pre>';
var_dump($same_list[0]);
var_dump($same_list[1]);
var_dump($same_list[2]);


$a2 = hrtime(true);
print "処理2実行後[メモリ使用量]：". memory_get_usage() / (1024 * 1024) ."MB<br>";
print "処理2実行後[メモリ最大使用量]：". memory_get_peak_usage() / (1024 * 1024) ."MB<br>";
print "-----------------------------------------------<br>";

// 2次元
$fp = fopen('log1.csv', 'a');
foreach ($data_list as $row) {
  fputs($fp, implode(',', $row)."\n");
}
fclose($fp);

// // 1次元
// $fp = fopen('log1.csv', 'a');
// foreach ($data_list as $row) {
//   fputs($fp, $row."\n");
// }
// fclose($fp);

echo '読込時間:'.(($a1 - $start) / 1000000000).'秒<br>';
echo 'ソート時間:'.((($a2 - $start) / 1000000000)-(($a1 - $start) / 1000000000)).'秒<br>';
echo '全体時間:'.(($a2 - $start) / 1000000000).'秒<br>';