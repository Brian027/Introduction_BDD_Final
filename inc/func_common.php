<?php


function dbug($var=''){
    if(is_object($var)){
        echo '<pre style="color:#FF0000">';
        var_dump($var);
        echo '</pre>';
        return '';
    }
    if(is_array($var)){

        // print_r no screen flush
        ob_start();
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        $dbug=ob_get_contents();
        ob_end_clean();

        $dbug='<div class="debug"><span ondblclick="this.parentElement.remove();">[ DEBUG ]</span><div>'.$dbug.'&nbsp;</div></div>';
        echo $dbug;
    }elseif($var===false){
        echo '<div class="debug"><span ondblclick="this.parentElement.remove();"span>[ DEBUG ]</span><div>FALSE&nbsp;</div></div>';
    }else{
        $dbug='<div class="debug"><span ondblclick="this.parentElement.remove();"span>[ DEBUG ]</span><div>'.$var.'&nbsp;</div></div>';
        echo $dbug;
    }
}

function userCanAdmin() {
    if(isset($_SESSION[SESSION_NAME]['id_user']) && $_SESSION[SESSION_NAME]['isAdmin']==1) {
        return true;
    } else {
        return false;
    }
}

function userConnected() {
    if(isset($_SESSION[SESSION_NAME]['id_user']) && $_SESSION[SESSION_NAME]['id_user']) {
        return true;
    } else {
        return false;
    }
}

function to_ajax($action, $id='void', $data=''){
    $action=strtolower($action);
    switch($action){
        case 'alert':
        case 'append':
        case 'after':
        case 'prepend':
        case 'set':
        case 'focus':
        case 'select':
        case 'class':
        case 'altsrc':
        case 'hide':
        case 'show':
        case 'wait':
        case 'endwait':
        case 'remove':
        case 'modal':
        case 'html':
        case 'location':
            echo $id.'<xfill type="'.$action.'">'.$data.'</xfill>';
            break;

    }
}

function to_ajax_eval($js){
    echo 'void<xfill type="eval">'.$js.'</xfill>';
}

function to_ajax_location($url=''){
    echo 'void<xfill type="location">'.$url.'</xfill>';
}
?>