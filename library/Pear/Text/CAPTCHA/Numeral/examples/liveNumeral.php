<?php
session_start();

require_once 'Text/CAPTCHA/Numeral.php';
$numcap = new Text_CAPTCHA_Numeral;

if (isset($_POST['captcha']) && isset($_SESSION['answer'])) {
    if ($_POST['captcha'] == $_SESSION['answer']) {
        $errors[] = 'Ok.. YOu might be human..';
    } else {
        $errors[] = 'You are or not human or dumb';
    }
}
    if (!empty($errors)) {
        foreach ($errors as $error) {
            print "<h1><font color='red'>$error</font></h1><br />";
        }
    }


    print '
        <form name="capter" action="'.$_SERVER['PHP_SELF'].'" method="post">
         <table>
          <tr>
           <th>What is this result pilgrim?: '.$numcap->getOperation().'</th>
           <td><input type="text" value="" name="captcha" /></td>
          </tr>
          <tr>
           <th/>
           <td><input type="submit" value="Let me prove you that I am human!" /></td>
          </tr>
        </form>
    ';
    $_SESSION['answer'] = $numcap->getAnswer();

