
<div class="navigation_wrapper">
    <ul>
<?php

for ($i=0;$i<count($menuItems);$i++){
    if ($menuItems[$i]->id == $currItemId){
        echo'<li class="selected">'."\n";
    } else {
        echo'<li>'."\n";
    }
    echo '<a href="'.site_url().'/admin/'.$menuItems[$i]->key.'">';
    echo $menuItems[$i]->menu_name;
    echo'</a>'."\n";
    echo'</li>'."\n";
}

?>
    </ul>
</div>