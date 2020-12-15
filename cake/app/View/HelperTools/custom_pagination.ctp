<?php if($pagination_params['pages'] > 1){ ?>
    <?php if($pagination_params['current_page'] > 1){ ?>
        <span>
            <?php 
                //Set first page
                $this->request->query['page'] = 1;
                //Show first page link
                echo $this->Html->link('prva strana', array(
                        'controller' => $pagination_params['controller'], 
                        'action' => $pagination_params['action'],
                        '?' => $this->request->query
                    ),
                    array('rel' => 'first')
                );
            ?>
        </span>
        <span class="prev">
            <?php 
                //Set previous page
                $this->request->query['page'] = $pagination_params['current_page'] - 1;
                //Set previous link
                echo $this->Html->link('« prethodna', array(
                        'controller' => $pagination_params['controller'],
                        'action' => $pagination_params['action'],
                        '?' => $this->request->query
                    )
                );
            ?>
        </span>
    <?php } ?>
    <?php
        $page_from = $pagination_params['current_page'] - 4;
        $page_to = $pagination_params['current_page'] + 4;

        if($page_from < 1){
            $page_diff = abs(1 - $pagination_params['current_page']);
            $page_from = 1;
            $page_to += 4 - $page_diff;
            if($page_to > $pagination_params['pages']){
                $page_to = $pagination_params['pages'];
            }
        }
        if($page_to > $pagination_params['pages']){
            $page_diff = abs($pagination_params['pages'] - $page_to);
            $page_to = $pagination_params['pages'];
            $page_from -= $page_diff;
            if($page_from < 1){
                $page_from = 1;
            }
        }
    ?>
    <?php for($i = $page_from; $i <= $page_to; $i++){ ?>
        <?php if($i == $pagination_params['current_page']){ ?>
            <span class="current"><?php echo $i; ?></span>
        <?php }else{ ?>
            <span>
                <?php 
                    //Set page number
                    $this->request->query['page'] = $i;
                    //Set page number link
                    echo $this->Html->link($i, array(
                        'controller' => $pagination_params['controller'],
                        'action' => $pagination_params['action'],
                        '?' => $this->request->query
                    ));
                ?>
            </span>
        <?php } ?>
        <?php if($i < $page_to){ ?>
            |
        <?php } ?>
    <?php } ?>
    <?php if($pagination_params['current_page'] != $pagination_params['pages']){ ?>
        <span class="next">
            <?php 
                //Set next page
                $this->request->query['page'] = $pagination_params['current_page'] + 1;
                //Set next page link
                echo $this->Html->link('sledeća »', array(
                    'controller' => $pagination_params['controller'], 
                    'action' => $pagination_params['action'],
                    '?' => $this->request->query
                ));
            ?>
        </span>
        <span>
            <?php
                //Set last page
                $this->request->query['page'] = $pagination_params['pages'];
                //Set last page link
                echo $this->Html->link('poslednja strana', array(
                        'controller' => $pagination_params['controller'], 
                        'action' => $pagination_params['action'],
                        '?' => $this->request->query
                    ),
                    array('rel' => 'last')
                );
            ?>
        </span>
    <?php } ?>
<?php } ?>