<div style="text-align: center;"> 
<?php if($pages > 1) {
    if($page > 1 && $page <= $pages) {
        echo $this->Html->link('prva strana', array(), array('style' => 'text-decoration: none;', 'onclick' => 'return false', 'class' => 'paglink', 'value' => 1));
        echo $this->Html->link(' «  prethodna', array(), array('style' => 'text-decoration: none;', 'onclick' => 'return false', 'class' => 'paglink', 'value' => $page-1));
    }
    if($pages < 10) {
        for($i = 0; $i < $pages; $i++) {
            if($i+1 != $page) {
                echo $this->Html->link($i+1, array(), array('style' => 'margin: 5px; text-decoration: none;', 'onclick' => 'return false', 'class' => 'paglink', 'value' => $i+1));
            } else {
                echo '<a style="margin:5px; color: #000;">'.($i+1).'</a>';
            }
            if($i+1 != $pages) echo '|';
        }
    } else {
        $offset = 1;
        if($page > 5 && $page < $pages - 3) $offset = $page - 4;
        if($page >= $pages - 4) $offset = $pages - 8;
        for($i = 0; $i < 9; $i++) {
            if($i+$offset != $page) {
                echo $this->Html->link($i+$offset, array(), array('style' => 'margin: 5px; text-decoration: none;', 'onclick' => 'return false', 'class' => 'paglink', 'value' => $i+$offset));
            } else {
                echo '<a style="margin:5px; color: #000;">'.($i+$offset).'</a>';
            }
            if($i + $offset != 8 + $offset) echo '|';
        }
    }
    if($page >= 1 && $page < $pages) {
        echo $this->Html->link('sledeća  » ', array(), array('style' => 'text-decoration: none;', 'onclick' => 'return false', 'class' => 'paglink', 'value' => $page+1));
        echo $this->Html->link('poslednja strana', array(), array('style' => 'text-decoration: none;', 'onclick' => 'return false', 'class' => 'paglink', 'value' => $pages));
    }
} ?>
</div>
