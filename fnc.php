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

// // ファイル読込(ソートから)
// function file_read($file_name) {
//   $src = ["\r\n", "\n", "\r"];
//   $i=0;
//   $fp = fopen($file_name, 'r');
//   // $row = fgets($fp); // 1行目のカラムを空読み
//   while ($row = fgets($fp)) {
//     // $col = [配列のid, ソートするカラム1, ソートするカラム2, ソートするカラム3]
//     $col[] = str_replace($src, '', $row);
//   }
//   return $col;
// }


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

/* 昇順マージソート
 * $list : ソートする配列
 * $be1  : ソートする前半データの開始位置
 * $be2  : ソートする前半データの件数
 * $af1  : ソートする後半データの開始位置
 * $af2  : ソートする後半データの件数
 * $col  : ソートするカラムの番号(配列の添字)
 */
function merge(&$list, $be1, $be2, $af1, $af2, $col) {
  $i=0; $j=0; $k=0;
  while ($k < $be2 + $af2) {
    if ($i >= $be2) {
      while ($j < $af2) {
        $tmp[$k++] = $list[$j++ + $af1];
      }
    }elseif($j >= $af2) {
      while ($i < $be2) {
        $tmp[$k++] = $list[$i++ + $be1];
      }
    }else{
      if ($list[$i + $be1][$col] <= $list[$j + $af1][$col]) {
        $tmp[$k++] = $list[$i++ + $be1];
      } else {
        $tmp[$k++] = $list[$j++ + $af1];
      }
    }
  }
  foreach ($tmp as $key => $val) {
    $list[$key + $be1] = $val;
  }
  return $list;
}