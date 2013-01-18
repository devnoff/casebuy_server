<div class="sub_navigation_wrapper">
    <ul>
<?php

for ($i=0;$i<count($menuItems);$i++){
    if ($menuItems[$i]->key == $currItemKey){
        echo '<li class="selected">'."\n";
    } else {
        echo '<li>'."\n";
    }
    echo '<a href="'.site_url().'/'.$menuItems[$i]->uri.'">';
    echo $menuItems[$i]->menu_name;
    echo '</a>'."\n";
    echo '</li>'."\n";
}

?>
    </ul>
</div>