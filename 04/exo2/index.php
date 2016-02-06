<?php
    if (isset($_POST)) { $word = translator($_POST['word']); }

    function translator($string) {
        $tranlate = array(
            'a' => 'y', 'b' => 'p', 'c' => 'l', 'd' => 't', 'e' => 'a', 'f' => 'v',
            'g' => 'k', 'h' => 'r', 'i' => 'e', 'j' => 'z', 'k' => 'g', 'l' => 'm',
            'm' => 's', 'n' => 'h', 'o' => 'u', 'p' => 'b', 'q' => 'x', 'r' => 'n',
            's' => 'c', 't' => 'd', 'u' => 'i', 'v' => 'j', 'w' => 'f', 'x' => 'q',
            'y' => 'o', 'z' => 'w'
        );

        $word = str_split($string);

        foreach ($word as $key => $value) {
            $word[$key] = $tranlate[$value];
        }

        return implode($word);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Al-Bhed translator</title>
    </head>

    <body>
        <p>
            Word to translate :
            <form action="" method="post">
                <input type="text" id="word" name="word" />
                <input type="submit" id="submit" name="submit" value="Envoyer" />
            </form>
        </p>
        <p><?php if (isset($word)) { echo $word; } ?></p>
    </body>
</html>