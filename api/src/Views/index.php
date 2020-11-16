<style>
    td {
        border:1px solid black;
        width: 30px;
        height: 30px;
    }
</style>

<table style="border:1px solid black;">
    <tbody>
    <?php
    for ($i = 0; $i < $data['garden']->getDimensions()['height']; $i++) {
        echo '<tr>';
        for ($j = 0; $j < $data['garden']->getDimensions()['width']; $j++) {
            echo '<td>';

            if (isset($data['plantsByLocation'][$i][$j])) {
                echo($data['plantsByLocation'][$i][$j]->getEnglishName());
            }
            echo '</td>';
        }
        echo '</tr>';
    }
    ?>
    </tbody>
</table>