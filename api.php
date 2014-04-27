<?php
    if(isset($_GET['username'])) {
        echo $message = trim(checkUsername($_GET['username']));
    }
    else{
        echo $message = 'No Username Given';
    }

    function checkUsername($username){
            if(strlen($username) > 16){return 'Too Long';}
            if(!preg_match('/^([A-Za-z0-9_])+$/', $username)){return 'Illegal Characters';}
            
            $json = uuid($username);

            $decoded = (array) json_decode($json);
            $firstProfile = (array) $decoded['profiles'][0];

            if(empty($decoded['profiles'])){
                return 'Available';
            }
            else{
                if(!isset($firstProfile['demo'])){
                    return 'Taken';
                }
                else{
                    return 'Taken Not Premium';
                }
            }
        }
        function uuid($username){
            $ch = curl_init();
            $curlConfig = array(
                CURLOPT_URL => "https://api.mojang.com/profiles/page/1",
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => '{
                        "name": "' . $username . '",
                        "agent": "minecraft"
                    }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            );
            curl_setopt_array($ch, $curlConfig);
            $json = curl_exec($ch);
            curl_close($ch);

            return $json;
    }
?>