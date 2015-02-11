<?php

/**
 * Data access wrapper for "orders" table.
 *
 * @author jim
 */
class Orders extends MY_Model {

    // constructor
    function __construct() {
        parent::__construct('orders', 'num');
        $this->load->model('Orderitems');
        $this->load->model('Menu');
    }

    // add an item to an order
    function add_item($num, $code) {
       if($this->Orderitems->exists($num, $code)){
            $prevRecord = $this->Orderitems->get($num, $code);
            $prevRecord->quantity = $prevRecord->quantity + 1;
            $this->Orderitems->update($prevRecord);
        }else{
            $record = $this->Orderitems->create();
            $record->order = $num;
            $record->item = $code;
            $record->quantity = 1;
            $this->Orderitems->add($record);
       }
    }

    // calculate the total for an order
    function total($num) {
        $sum = 0.0;
        $cartitems = $this->Orderitems->some('order', $num);
        foreach($cartitems as $singleitem){
            $qty = $singleitem->quantity;
            $recMenuItem = $this->Menu->get($singleitem->item);
            $price = $recMenuItem->price;
            $sum = $sum + ($qty * $price);
        }
        return $sum;
    }

    // retrieve the details for an order
    function details($num) {
        
    }

    // cancel an order
    function flush($num) {
        
    }

    // validate an order
    function validate($num) {
        $isM = false; //default to check categories
        $isD = false;
        $isS = false;
        $allOrderedItems = $this->Orderitems->some('order', $num);
        foreach ($allOrderedItems as $orderedItem){
            $menuItem = $this->Menu->get($orderedItem->item);
            if($menuItem->category == 'm'){
                $isM = true;
            }
            if($menuItem->category == 'd'){
                $isD = true;
            }
            if($menuItem->category == 's'){
                $isS = true;
            }
        }
        
        if($isM and $isD and $isS){
            return true;
        }
            return false;
    }

}
