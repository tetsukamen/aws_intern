<?php
    ini_set('display_errors', 1); // エラーを表示する

    include 'functions.php'; // 関数の読み込み

    if(isset($_GET)){
        $function = NULL;
        $name = NULL;
        $amount = NULL;
        $price = NULL;
        // パラメータのパース
        if(!empty($_GET['function'])){
            $function = $_GET['function'];
        }
        if(!empty($_GET['name'])){
            $name = $_GET['name'];
        }
        if(!empty($_GET['amount'])){
            $amount = $_GET['amount'];
        }
        if(!empty($_GET['price'])){
            $price = $_GET['price'];
        }
    } else {
        exit("ERROR");
    }
    
    // パラメータの値のチェックし、関数名に応じて実行
    if( $function==='addstock' && strlen($name)>0 ){
        addstock($name,$amount);
    } elseif( $function==='checkstock' ){
        checkstock($name);
    } elseif( $function==='sell' && strlen($name)>0 ){
        sell($name,$amount,$price);
    } elseif( $function==='checksales' ){
        checksales();
    } elseif( $function==='deleteall' ){
        deleteall();
    } else {
        exit("ERROR");
    }

?>