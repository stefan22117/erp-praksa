
<style>
    #notificationListAlt {
        width: 100%;
        padding: 10px;
        background: #ddd;
        position: absolute;
        top: 50px;
        left: 1px;
        z-index: 1000;
        border: 1px solid #bbb;
    }
    #notificationListAlt li {
        background: #eee;
        margin: 5px;
        padding: 5px 10px;
        list-style-type: none;
        /* width: 400px; */
        border-radius: 3px;
        border: 1px solid #bbb;
    }
    #notificationListAlt li a {
        text-decoration: none;
    }
</style>

<ul id="notificationListAlt" style="display: none;">
<?php foreach ($lastNotifications as $lastNotification): ?>
    <?php
        $ncolor = '#efe';
        if ($lastNotification['Notification']['read']) $ncolor = '#fff';
        $str = $lastNotification['Notification']['title'].': ';
        if (!empty($lastNotification['Notification']['document_code'])) $str .=  $lastNotification['Notification']['document_code'].' - ';
        $str .= $lastNotification['Notification']['document_title'];
    ?>

    <li style="background: <?php echo $ncolor ?>" >
    <div style="font-size: 10px;">
    <?php echo $this->Time->format('d. M',$lastNotification['Notification']['created']).' - '.$this->Time->format('H:i', $lastNotification['Notification']['created'])?>
    </div>
    <?php echo $this->Html->link($str, array('controller' => $lastNotification['Notification']['link_controller'], 'action' => $lastNotification['Notification']['link_action'], $lastNotification['Notification']['document_id']), array('target' => '_blank')); ?></li>
<?php endforeach; ?>
<li style="background: #fff; text-align: center; padding:0;"><?php echo $this->Html->link('Prikaži sve notifikacije', array('controller' => 'Notifications', 'action' => 'index'), array('class' => 'btn', 'style' => 'width:100%'));?></li>
</ul>
<script>
    var show = 0;
    $(document).ready(function(){
        $('#urNotification').click(function(){
            show++;
            $('#notificationListAlt').toggle();
            $.ajax({
                url: "<?php
                    echo $this->Html->url(
                        array(
                            'controller' => 'Notifications',
                            'action' => 'readAllNotifications',
                            $userId
                        )
                    );
                ?>",
                success: function(result) {
                }
            });
            if (show == 2) {
                $('#unreadCount').fadeOut();
                $('#notificationListAlt li').css('background', '#fff');
                $('#notificationListAlt li:last').css('background', '#fff');
            }
        });
        $('#notificationListAlt').css('width', $(window).width() - 40);
    });
</script>