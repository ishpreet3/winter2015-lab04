<?php

/**
 * Order handler
 * 
 * Implement the different order handling usecases.
 * 
 * controllers/welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Order extends Application {

    function __construct() {
        parent::__construct();
        $this->load->model('Orders');
        $this->load->model('Orderitems');
        $this->load->model('Menu');
    }

    // start a new order
    function neworder() {
        $order_num = $this->Orders->highest() + 1;
        $date = date('Y/m/d H:i:s');
        $this->Orders->add( array('num' => $order_num,  'date' => $date, 
                                     'status' => 'a', 'total' => $this->Orders->total($order_num)));
        redirect('/order/display_menu/' . $order_num);
    }

    // add to an order
    function display_menu($order_num = null) {
        if ($order_num == null)
            redirect('/order/neworder');

        $this->data['pagebody'] = 'show_menu';
        $this->data['order_num'] = $order_num;
        //FIXME <- added the title tag
        $this->data['title'] = 'Order # ' . $order_num . ' ($' . $this->Orders->total($order_num) . ')';
        // Make the columns
        $this->data['meals'] = $this->make_column('m');
        $this->data['drinks'] = $this->make_column('d');
        $this->data['sweets'] = $this->make_column('s');

        $this->render();
    }

    // make a menu ordering column
    function make_column($category) {
        //FIXME <- added this line to get the categories
        $items = $this->Menu->some('category', $category); 
        return $items;
    }

    // add an item to an order
    function add($order_num, $item) {
        $this->Orders->add_item($order_num, $item);
        redirect('/order/display_menu/' . $order_num);
    }

    // checkout
    function checkout($order_num) {
        $this->data['title'] = 'Checking Out';
        $this->data['pagebody'] = 'show_order';
        $this->data['order_num'] = $order_num;
        //FIXME <- gets a list of all the ordered items and puts the important info in to an array
        $items = array();
        $allOrderedItems = $this->Orderitems->some('order', $order_num);
        foreach ($allOrderedItems as $orderedItem){
            $item_details = $this->Menu->get($orderedItem->item);
            $items[] = array('quantity' => $orderedItem->quantity, 'code' => $item_details->code,
                              'price' => $item_details->price, 'name' => $item_details->name);
        }
        $this->data['items'] = $items;
        $this->data['total'] = $this->Orders->total($order_num);
        if($this->Orders->validate($order_num))
        {
            $this->render();
        }
        else {
            echo "<script type='text/javascript'>\n";
            echo "alert('Error: You need one item from each category to proceed, use the back button in your browser to continue shopping.');\n";
            echo "</script>";
        }
        
    }

    // proceed with checkout
    function proceed($order_num) {
        //FIXME <- updates the orders table with the total and as completed then returns to the home page
        $date = date('Y/m/d H:i:s');
        $record = array('num' => $order_num,  'date' => $date, 
                                     'status' => 'c', 'total' => $this->Orders->total($order_num));
        $this->Orders->update($record);
        redirect('/');
    }

    // cancel the order
    // delete all items from this order in the orderitems table
    function cancel($order_num) {
        //FIXME <-deletes the current items in the orderitems table for the current order, and marks order as cancelled (x)
        $date = date('Y/m/d H:i:s');
        $record = array('num' => $order_num,  'date' => $date, 
                                     'status' => 'x', 'total' => $this->Orders->total($order_num));
        $this->Orders->update($record);
        $this->Orderitems->delete_some($order_num);
        redirect('/');
    }

}
