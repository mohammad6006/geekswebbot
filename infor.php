<?php
$survfile = file_get_contents("./survivor.json");
$json_surv=json_decode($survfile);
print_r($json_surv);
$btns = [];
foreach($json_surv->unluler as $value) {
        echo $value->slug;
    }
print_r($btns);
// require 'vendor/autoload.php';

// $token = getenv('acstok');
// $dbopts = parse_url(getenv('DATABASE_URL'));
// $dsn = 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"];
// $user = $dbopts["user"];
// $pw = $dbopts["pass"];
// $pdo = new PDO($dsn, $user, $pw);
// $fpdo = new FluentPDO($pdo);

// $query = $fpdo->from('messages');
// foreach ($query as $row) {
//     echo "$row[user_id] \n $row[chat_id] \n $row[message_id] \n $row[daryaft] \n $row[ersal] \n";
// }

// $query = $fpdo->from('messages')->where('user_id', 123);
// $values = array('user_id' => '123', 'chat_id' => '321', 'message_id' => '456', 'daryaft' => 'abc', 'ersal' => 'def');       
//     $query = $fpdo->insertInto('messages')->values($values);    
//     $insert = $query->execute();

    // var_dump($query);
    // will output:
    // string(1) "3"
