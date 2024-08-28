<?php
/**
 * Table Definition for imoveis_melhoramentos
 */
require_once '../pear/DB/DataObject.php';

class Imoveis_melhoramentos extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'imoveis_melhoramentos';    // table name
    public $imovel_id;                       // int(11)  not_null primary_key multiple_key
    public $melhoramento_id;                 // int(11)  not_null primary_key multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Imoveis_melhoramentos',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function exist($imovel, $melhoramento) {
        
        $exist = false;
        $im = new Imoveis_melhoramentos();
        $im->whereAdd('imovel_id = '.$imovel);
        $im->whereAdd('melhoramento_id = '.$melhoramento);
        $im->find();
        while ($im->fetch()) {
            $exist = true;
        }
        
        return $exist;
        
    }
    
}
