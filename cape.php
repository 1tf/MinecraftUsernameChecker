<?php
    if(isset($_GET['username'])) {
        echo $message = checkCape(trim($_GET['username']));
    }
    else{
        echo $message = 'No Username Given';
    }

    function checkCape($username){
        $nExists = (@$fp = fopen("http://skins.minecraft.net/MinecraftCloaks/" . $username . ".png", "r")) !== FALSE; 

        //Optifine
        
        $ofexists = (@$offp = fopen("http://s.optifine.net/capes/" . $username . ".png", "r")) !== FALSE; 

        if($ofexists == false && $nExists == false){
            echo 'No Cape';
        }

        elseif($ofexists == true && $nExists == false){
            echo 'Optifine';
        }

        elseif($ofexists == false && $nExists == true){
            return 'Minecraft';
        }

        elseif($ofexists == true && $nExists == true){
            return 'Both';
        }
        fclose($fp);
        fclose($offp); 
    }
?>