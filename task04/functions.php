<?php
// 関数の定義

// 在庫の追加
function addstock($name,$amount){
    $valid_amount = is_valid_amount($amount);
    $db_link = mysqli_connect('localhost', 'root', '', 'stocker');
    // 接続チェック
    if (!$db_link) {
      die('データベースの接続に失敗しました。');
    }
    mysqli_set_charset( $db_link, 'utf8');
    $sql = "INSERT INTO item (name, increase_amount, decrease_amount, price) VALUES ('$name', '$valid_amount', 0, 0)";
    $result = mysqli_query($db_link,$sql);
    echo mysqli_error($db_link);
    mysqli_close($db_link);
}

// 在庫チェック
function checkstock($name){
    if(strlen($name)>0){
        // $nameのストックをチェック
        $db_link = mysqli_connect('localhost', 'root', '', 'stocker');
        // 接続チェック
        if (!$db_link) {
          die('データベースの接続に失敗しました。');
        }
        mysqli_set_charset( $db_link, 'utf8');
        $sql = "SELECT * FROM item WHERE name='$name'";
        $result = mysqli_query($db_link,$sql);
        $stock_amount = 0;
        while ($row = mysqli_fetch_array($result)) {
            $stock_amount = $stock_amount + $row['increase_amount'] -  $row['decrease_amount'];
        }
        echo $name.":　".$stock_amount;
        echo mysqli_error($db_link);
        mysqli_free_result($result);
        mysqli_close($db_link);
    } else {
        // 全ての在庫のストックをチェック
        $db_link = mysqli_connect('localhost', 'root', '', 'stocker');
        // 接続チェック
        if (!$db_link) {
          die('データベースの接続に失敗しました。');
        }
        mysqli_set_charset( $db_link, 'utf8');
        $sql = "SELECT * FROM item";
        $result = mysqli_query($db_link,$sql);
        $items_arr = array();
        while ($row = mysqli_fetch_array($result)) {
            $item_name = $row['name'];
            if(isset($items_arr[$item_name])){
                $items_arr[$item_name] = $items_arr[$item_name] + $row['increase_amount'] -  $row['decrease_amount'];
            } else {
                $items_arr[$item_name] = $row['increase_amount'] -  $row['decrease_amount'];
            }
        }
        ksort($items_arr);
        foreach($items_arr as $key => $value){
            if($value>0){
                echo $key.": ".$value."\n";
            }
        }
        echo mysqli_error($db_link);
        mysqli_free_result($result);
        mysqli_close($db_link);
    }
}

// 販売
function sell($name,$amount,$price){
    $valid_amount = is_valid_amount($amount);
    $valid_price = is_valid_price($price);
    $db_link = mysqli_connect('localhost', 'root', '', 'stocker');
    // 接続チェック
    if (!$db_link) {
      die('データベースの接続に失敗しました。');
    }
    mysqli_set_charset( $db_link, 'utf8');
    $sql = "INSERT INTO item (name, increase_amount, decrease_amount, price) VALUES ('$name', 0, '$valid_amount', '$valid_price')";
    $result = mysqli_query($db_link,$sql);
    echo mysqli_error($db_link);
    mysqli_close($db_link);
}

// 売り上げチェック
function checksales(){
    $db_link = mysqli_connect('localhost', 'root', '', 'stocker');
    // 接続チェック
    if (!$db_link) {
        die('データベースの接続に失敗しました。');
      }
    mysqli_set_charset( $db_link, 'utf8');
    $sql = "SELECT * FROM item";
    $result = mysqli_query($db_link,$sql);
    $total_sales = 0;
    while ($row = mysqli_fetch_array($result)) {
        $total_sales += $row['decrease_amount'] * $row['price'];
    }
    if($total_sales == 0){
        $display_sales = 0;
    } else {
        $display_sales = ceil_plus($total_sales);
    }
    echo "sales: ".$display_sales;
    echo mysqli_error($db_link);
    mysqli_free_result($result);
    mysqli_close($db_link);
}

// 全削除
function deleteall(){
    $db_link = mysqli_connect('localhost', 'root', '', 'stocker');
    // 接続チェック
    if (!$db_link) {
        die('データベースの接続に失敗しました。');
      }
    mysqli_set_charset( $db_link, 'utf8');
    $sql = "TRUNCATE TABLE item";
    $result = mysqli_query($db_link,$sql);
    echo mysqli_error($db_link);
    mysqli_close($db_link);
}

// 正の整数であるかチェックする。正の整数を返す
function is_valid_amount($value){
    if($value==NULL){ // 引数がNULLの場合は1を返す
        return 1;
    } else {
        if(!preg_match('/\./',$value)){ // 小数点を含まないことをチェック
            $casted = (int) $value; // 整数にキャスト
            if($casted>0){ // 引数が正の数かどうか判定
                return $casted;
            } else {
                exit("ERROR");
            }
        } else {
            exit("ERROR");
        }
        
    }
}

// 0より大きい数値であるかチェックする。０または正の数を返す。
function is_valid_price($value){
    if($value==NULL){ // 引数がNULLの場合は0を返す
        return 0;
    } else {
        $casted = (float) $value; // 不動小数点にキャスト
        if($casted>0){ // 引数が正の数かどうか判定
            return $casted;
        } else {
            exit("ERROR");
        }
    }
}

// 小数点第２位まで切り上げする
function ceil_plus($value, $precision = 2) {
    return round($value + 0.5 * pow(0.1, $precision), $precision, PHP_ROUND_HALF_DOWN);
  }
?>