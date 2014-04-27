<?php
    if(isset($_REQUEST['username'])) {
        $message = checkUsername($_REQUEST['username']);
    }

    function checkUsername($username){
            if(strlen($username) > 16){return '<span style="color: red;">Invalid size!</span>';}
            if(!preg_match('/^([A-Za-z0-9_])+$/', $username)){return '<span style="color: red;">Illegal characters!</span>';}
            
            $json = uuid($username);

            $decoded = (array) json_decode($json);
            $firstProfile = (array) $decoded['profiles'][0];
            $name = $firstProfile['name'];
            $cape = cape($username);

            if(empty($decoded['profiles'])){
                return '<span style="color: green;">Username is available!</span>';
            }
            else{
                if(!isset($firstProfile['demo'])){
                    return '<span style="color: red;">Username is taken '. $cape .'</span>';
                }
                else{
                    return '<span style="color: red;">Username is taken, but is not premium!</span>';
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

    function cape($username){
        $nExists = (@$fp = fopen("http://skins.minecraft.net/MinecraftCloaks/" . $username . ".png", "r")) !== FALSE; 

        //Optifine
        
        $ofexists = (@$offp = fopen("http://s.optifine.net/capes/" . $username . ".png", "r")) !== FALSE; 

        if($ofexists == false && $nExists == false){
            return " and has no cape!";
        }

        elseif($ofexists == true && $nExists == false){
            return " and has an Optifine cape!";
        }

        elseif($ofexists == false && $nExists == true){
            return " and has a Minecraft cape!";
        }

        elseif($ofexists == true && $nExists == true){
            return " and has both a Minecraft and Optifine cape!";
        }
        fclose($fp);
        fclose($offp); 
    }
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://minotar.net/avatar/jrneulight/128.png">

    <title>Minecraft Username Checker</title>
    
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">
        @font-face{
            font-family: 'Minecraftia';
            src: url("fonts/Minecraftia.ttf") format('truetype');

        }
        .minecraft-font{
            font-family: 'Minecraftia';
        }
    </style>

    <script>

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-43185554-4']);
      _gaq.push(['_trackPageview']);
      (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; 

      ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';

      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
</head>
<body>

        <div class="container">
            <div class="text-center">
                <h1 class="minecraft-font">Minecraft Username/Cape Checker</h1>
                <h4 class="text-muted">Created by jrneulight</h4>
            </div>
            <br/>
            <div class="text-center" style="width: 400px; margin: 0 auto;">    
                <h3><?php if(isset($message)){ echo $message; }?></h3>
                <form method="post" action="">
                    <input type="text" placeholder="Username" value="<?php if (isset($_REQUEST['username'])) {echo $_REQUEST['username'];} ?>" class="form-control" name="username" required style="height: 100px;width: 400px;font-size:250%;">
                    <br/>
                    <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Check">
                </form>
            </div>
            <hr>
            <h2 style="width: 590px;margin: 0 auto;" class="well well-sm text-center">Why are we better than all the others?</h2>
            
            <div class="col-lg-4">
              <h3>We don't just check for premium</h3>
              <p>We actually check to see if the username is available, unlike like other Minecraft Username Checkers. It is 100% accurate, all of the time.</p>
              <br/>
              <span style="font-size:200%;"><center>Sponsered by:</center></span>
              <center><img src="img/tb.png" style="width: 346px; height: 45px;"></center>
              <span style="font-size:125%;"><center>minecraft.technobuffalo.com</center></span>
              <br/>
              <span style="font-size:100%;"><center>and</center></span>
              <center><img src="img/mcc.png"></center>
              <span style="font-size:125%;"><center>MC-Crime</center></span>
            </div>

            <div class="col-lg-4">
              <h3>Powerful & Simple API</h3>
              <p>The API is simple to use:
              <code>http://checkna.me/api/UsernameHere</code>
              <br/>
              <br/>
              It will return:
              <br/>
              <br/>
              <code>Available</code> - If the username is available
              <br/>
              <br/>
              <code>Taken</code> - If the username is taken
              <br/>
              <br/>
              <code>Taken Not Premium</code> - If the username is taken and not premium
              <br/>
              <br/>
              <code>Illegal Characters</code> - If the username contains characters that are not allowed in a Minecraft username
              <br/>
              <br/>
              <code>Too Long</code> - If the username is longer than 16 characters
              <br/>
              <br/>
              <code>No Username Given</code> - If no username was specified
              </p>
            </div>
            

            <div class="col-lg-4">
              <h3>Awesome Cape API</h3>
              <p>The Cape API is simple to use:
              <code>http://checkna.me/cape/UsernameHere</code>
              <br/>
              <br/>
              It will return:
              <br/>
              <br/>
              <code>No Cape</code> - If the user has no cape
              <br/>
              <br/>
              <code>Minecraft</code> - If the user has a Minecraft cape
              <br/>
              <br/>
              <code>Optifine</code> - If the user has an Optifine cape
              <br/>
              <br/>
              <code>Both</code> - If the user has a Minecraft cape and an Optifine cape
              <br/>
              <br/>
              <code>No Username Given</code> - If no username was specified
              </p>
            </div>
            
            </div>

        </div>

        <div style="margin: 0 auto; width: 92px;height: 26px;">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_donations">
            <input type="hidden" name="business" value="icraftjrneulight@gmail.com">
            <input type="hidden" name="lc" value="US">
            <input type="hidden" name="item_name" value="Minecraft Username Checker">
            <input type="hidden" name="no_note" value="0">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </div>
        <br/>
        <div class="text-muted text-center">&copy;2014 - All Rights Reserved</div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</body>
</html>