<?php

// ファイル読込(ソートから)
function file_read($file_name) {
  $src = ["\r\n", "\n", "\r"];
  $i=0;
  $fp = fopen($file_name, 'r');
  // $row = fgets($fp); // 1行目のカラムを空読み
  while ($row = fgets($fp)) {
    // $col = [配列のid, ソートするカラム1, ソートするカラム2, ソートするカラム3]
    $col[] = [ $i++, explode(',', str_replace($src, '', $row))[4], explode(',', str_replace($src, '', $row))[1], explode(',', str_replace($src, '', $row))[6] ];
  }
  return $col;
}

/* 中間値の取得
 * $x : ソート対象の先頭の値
 * $y : ソート対象の中間の値
 * $z : ソート対象の最後の値
 */
function pivot($x, $y, $z) {
  if ($x < $y) {
    if ($y < $z) {
      return $y;
    } elseif ($z < $x) {
      return $x;
    } else {
      return $z;
    }
  } else {
    if ($z < $y) {
      return $y;
    } elseif ($x < $z) {
      return $x;
    } else {
      return $z;
    }
  }
}

/* 昇順クイックソート
 * $data : ソートする配列
 * $l    : ソートするデータの開始位置
 * $r    : ソートするデータの終了位置
 * $num  : ソートするカラムの番号(配列の添字)
 */
function q_sort_a(&$data, $l, $r, $num) {
  if ($l < $r) {
    $wl = $l;
    $wr = $r;
    $pivot = pivot($data[$l][$num], $data[($wl + $wr) / 2][$num], $data[$r][$num]);
    while (1) {
      while ($data[$wl][$num] < $pivot) $wl++;
      while ($data[$wr][$num] > $pivot) $wr--;
      if ($wl >= $wr) break;
      $work = $data[$wr];
      $data[$wr] = $data[$wl];
      $data[$wl] = $work;
      $wl++; $wr--;
    }
    $data = q_sort_a($data, $l, $wl-1, $num);
    $data = q_sort_a($data, $wr+1, $r, $num);
  }
  return $data;
}

/* 降順クイックソート
 * $data : ソートする配列
 * $l    : ソートするデータの開始位置
 * $r    : ソートするデータの終了位置
 * $num  : ソートするカラムの番号(配列の添字)
 */
function q_sort_d(&$data, $l, $r, $num) {
  if ($l < $r) {
    $wl = $l;
    $wr = $r;
    $pivot = pivot($data[$l][$num], $data[($wl + $wr) / 2][$num], $data[$r][$num]);
    while (1) {
      while ($data[$wl][$num] > $pivot) $wl++;
      while ($data[$wr][$num] < $pivot) $wr--;
      if ($wl >= $wr) break;
      $work = $data[$wr];
      $data[$wr] = $data[$wl];
      $data[$wl] = $work;
      $wl++; $wr--;
    }
    $data = q_sort_d($data, $l, $wl-1, $num);
    $data = q_sort_d($data, $wr+1, $r, $num);
  }
  return $data;
}

/* 昇順マージ
 * $list : ソートする配列
 * $b_start  : ソートする前半データの開始位置
 * $b_num  : ソートする前半データの件数
 * $a_start  : ソートする後半データの開始位置
 * $a_num  : ソートする後半データの件数
 * $col  : ソートするカラムの番号(配列の添字)
 */
function merge(&$list, $b_start, $b_num, $a_start, $a_num, $col) {
  $i=0; $j=0; $k=0;
  while ($k < $b_num + $a_num) {
    if ($i >= $b_num) {
      while ($j < $a_num) {
        $tmp[$k] = $list[$j + $a_start];
        $k++; $j++;
      }
    } elseif($j >= $a_num) {
      while ($i < $b_num) {
        $tmp[$k] = $list[$i + $b_start];
        $k++; $i++;
      }
    } else {
      // echo $a_start.",".$j.",".$a_num;
      if ($list[$i + $b_start][$col] <= $list[$j + $a_start][$col]) {
        $tmp[$k] = $list[$b_start + $i];
        $k++; $i++;
      } else {
        $tmp[$k] = $list[$a_start + $j];
        $k++; $j++;
      }
    }
  }
  foreach ($tmp as $key => $val) {
    $list[$key + $b_start] = $val;
  }
  return $list;
}

/* 重複検出
 * 
 */
function double_check(&$list) {
  $cnt=0;
  $same_list = [];
  for ($i=0; $i < count($list)-1; $i++) {
    $j = $i;
    // 一致し続ける間の範囲の取得
    while ($list[$i][2] == $list[$j + 1][2]) {
      $j++;
      if ($j >= count($list)-1) break;
    }
    // 同じ値があった時、先頭と末尾の値を配列に格納
    if ($i != $j) {
      $same_list[] = [$i, $j];
      $i = $j;
    }
  }
  return $same_list;
}

/* コムソート
 * 
 * 
 */
function comb_sort($list) {
  $h = count($list) / 1.3;
  while (1) {
    $swaps = 0;
    for ($i=0; $i + $h < count($list); ++$i) { 
      if ($list[$i] > $list[$i + $h]) {
        
        ++$swaps;
      }
    }
  }
}