<?php
    function viewMenu($items, $viewObj, $parent = NULL, $data = "")
    {       
        $i = ($parent == NULL) ? '' : $parent;
        if(empty($items[$i])) {
            return $data;
        }
        $data .= "<ul class='menu menu_no_border'>";
        foreach ($items[$i] as $child) {
            $data .= "<li>";
            $data .= $viewObj->Html->link('<i class="'.$child['ErpKickstartIcon']['icon_class'].'"></i> '.__d('menu', $child['MenuItem']['name']), array('controller' => $child['MenuItem']['controller'], 'action' => $child['MenuItem']['action'], $child['MenuItem']['params']), $child['MenuItem']['options']);
            $data = viewMenu($items, $viewObj, $child['MenuItem']['id'], $data);
            $data .= "</li>";
        }
        $data .= "</ul>";
        return $data;
    }
?>
<style>
    .menu li {
        font-size: 15px;
    }
    #frame {
        position: relative;
    }
    #notificationList {
        display: none;
        width: 430px;
        padding: 10px;
        background: #ddd;
        position: absolute;
        top: 50px;
        right: 1px;
        z-index: 1000;
        border: 1px solid #bbb;
    }
    #notificationList li {
        background: #eee;
        margin: 5px;
        padding: 5px 10px;
        list-style-type: none;
        width: 400px;
        border-radius: 3px;
        border: 1px solid #bbb;
    }
    #notificationList li a {
        text-decoration: none;
    }
</style>
<?php if(isset($itemsMenu) && !$unreadNotificationCount): ?>
<style>
    #unreadNotification {
        display: none;
    }
</style>
<?php endif; ?>
<?php
    if(isset($itemsMenu)) {
        $menu = viewMenu($itemsMenu, $this);
        $menu = substr($menu, 0, -5);
        $menu .= '<li style="float:right">' . $this->Html->link('<i class="icon-user"></i> <span style="font-weight:bold; color: #080; font-size: 85%">'.$userName.'</span>', array('controller' => '', 'action' => '', "\0"), array('escape' => false, 'onclick' => 'return false'));
        $menu .= '<ul style="left:auto; right:0;">';
        $menu .= '<li>' . $this->Html->link('<i class="icon-envelope-alt"></i> '.__d('menu', 'Feedbacks'), array('controller' => 'Feedbacks', 'action' => 'index'), array('escape' => false)) . '</li>';
        $menu .= '<li>' . $this->Html->link('<i class="icon-info-sign"></i> '.__d('menu', 'Notifikacije'), array('controller' => 'Notifications', 'action' => 'index'), array('escape' => false)) . '</li>';
        $menu .= '<li>' . $this->Html->link('<i class="icon-signout"></i> '.__d('menu', 'Odjava'), array('controller' => 'Users', 'action' => 'logout'), array('escape' => false)) . '</li>';
        $menu .= '</ul>';
        $menu .= '</li>';
        $menu .= '<li id="notificationItem" style="float:right;">' . $this->Html->link('<span style="font-size: 120%"><i class="icon-bell"></i><div id="unreadNotification" style="position: relative; width: 18px; background: #a00; color: #fff; margin-bottom: -19px; padding: 2px; border-radius: 20px; top: -10px; left: 11px; font-size: 11px; text-align: center;">'.$unreadNotificationCount.'</div></span>', array('controller' => '', 'action' => '', "\0"), array('escape' => false, 'onclick' => 'return false', 'id' => 'notification', 'title' => 'Notifikacije'));
        $menu .= '</li>';
        $menu .= '</ul>';

        $menu .= '<ul id="notificationList">';
        foreach ($lastNotifications as $lastNotification) {
            $ncolor = '#efe';
            if ($lastNotification['Notification']['read']) $ncolor = '#fff';
            $str = $lastNotification['Notification']['title'].': ';
            if (!empty($lastNotification['Notification']['document_code'])) $str .=  $lastNotification['Notification']['document_code'].' - ';
            $str .= $lastNotification['Notification']['document_title'];
            $menu .= '<li style="background:'.$ncolor.'" ><div style="font-size: 10px;">'.$this->Time->format('d. M',$lastNotification['Notification']['created']).' - '.$this->Time->format('H:i', $lastNotification['Notification']['created']).'</div>'.$this->Html->link($str, array('controller' => $lastNotification['Notification']['link_controller'], 'action' => $lastNotification['Notification']['link_action'], $lastNotification['Notification']['document_id']), array('target' => '_blank')).'</li>';
        }
        $menu .= '<li style="background: #fff; text-align: center; padding:0;">'.$this->Html->link('Prikaži sve notifikacije', array('controller' => 'Notifications', 'action' => 'index'), array('class' => 'btn', 'style' => 'width:100%')).'</li>';
        $menu .= '</ul>';

        echo $menu;
    } else { ?>
        <ul class="menu menu_no_border">
            <center><li><a href="#" onclick="return false" class="cursor"><b>mikroERP</b></a></li></center>
        </ul>
    <?php }
?>
<script>
    var show = 0;
    $(document).ready(function(){
        $('#notification').click(function(){
            show++;
            $('#notificationList').toggle();
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
                    $('#unreadNotification').fadeOut();
                }
            });
            if (show == 2) {
                $('#notificationList li').css('background', '#fff');
                $('#notificationList li:last').css('background', '#fff');
            }
        });
        if (window.innerWidth < '1100') {
            $('#notificationItem').hide();
			$('.has-menu').removeAttr('style');
        }
    });
</script>
