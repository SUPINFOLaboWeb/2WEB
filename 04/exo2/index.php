<?php
    function translator($string) {
        $tranlate = array(
            'a' => 'y', 'b' => 'p', 'c' => 'l', 'd' => 't', 'e' => 'a', 'f' => 'v',
            'g' => 'k', 'h' => 'r', 'i' => 'e', 'j' => 'z', 'k' => 'g', 'l' => 'm',
            'm' => 's', 'n' => 'h', 'o' => 'u', 'p' => 'b', 'q' => 'x', 'r' => 'n',
            's' => 'c', 't' => 'd', 'u' => 'i', 'v' => 'j', 'w' => 'f', 'x' => 'q',
            'y' => 'o', 'z' => 'w'
        );

        $word = explode('.', $string);

        foreach ($word as $key => $value) {
            $word[$key] = $tranlate[$value];
        }

        return implode('.', $word);
    }

    // Exercise 4/4
    function translatorWithoutDot($string) {
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
        <p>a.p.r.i.c.o.t = <?php echo(translator('a.p.r.i.c.o.t')); ?></p>
        <p>apricot = <?php echo(translatorWithoutDot('apricot')); ?></p>
    </body>
</html>