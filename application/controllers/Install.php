<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Install extends CI_Controller {
 
    var $db_driver = 'mysql';
    var $db_prefix = '';
 
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');       
    }
 
    /**
     * First Step
     *
     * install the tables in the database
     */
    function index()
    {
        if($this->_install_database())
        {
                echo "SUCCESSFULL INSTALLATION : The tables have been created in the database. Go to line 374 of the file /application/config/config.php and change the value to YES instead of NO";  ;
                $this->config->load('config');
                $this->config->set_item('installed','yes');
        }
        else
        {
            show_error('there was a problem installing to the database');
        }
          
    }


    /**
     * Do Install
     */
    function _install_database()
    {
        $this->load->database();

        $this->load->model('install_model');
        
        $sql = " DROP TABLE IF EXISTS `menu`;
                CREATE TABLE IF NOT EXISTS `menu` (
                  `code` int(11) NOT NULL,
                  `name` varchar(32) NOT NULL,
                  `description` varchar(256) NOT NULL,
                  `price` decimal(10,2) NOT NULL,
                  `picture` varchar(100) NOT NULL,
                  `category` varchar(1) NOT NULL,
                  PRIMARY KEY (`code`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                
                INSERT INTO `menu` (`code`, `name`, `description`, `price`, `picture`, `category`) VALUES
                (1, 'Cheese', 'Leave this raw milk, beefy and sweet cheese out for an hour before serving and pair with pear jam.', '2.95', '1.png', 's'),
                (2, 'Turkey', 'Roasted, succulent, stuffed, lovingly sliced turkey breast', '5.95', '2.png', 'm'),
                (6, 'Donut', 'Disgustingly sweet, topped with artery clogging chocolate and then sprinkled with Pixie dust', '1.25', '6.png', 's'),
                (10, 'Bubbly', '1964 Moet Charmon, made from grapes crushed by elves with clean feet, perfectly chilled.', '14.50', '10.png', 'd'),
                (11, 'Ice Cream', 'Combination of decadent chocolate topped with luscious strawberry, churned by gifted virgins using only cream from the Tajima strain of wagyu cattle', '3.75', '11.png', 's'),
                (8, 'Hot Dog', 'Pork trimmings mixed with powdered preservatives, flavourings, red colouring and drenched in water before being squeezed into plastic tubes. Topped with onions, bacon, chili or cheese - no extra charge.', '6.90', '8.png', 'm'),
                (25, 'Burger', 'Half-pound of beef, topped with bacon and served with your choice of a slice of American cheese, red onion, sliced tomato, and Heart Attack Grill''s own unique special sauce.', '9.99', 'burger.png', 'm'),
                (21, 'Coffee', 'A delicious cup of the nectar of life, saviour of students, morning kick-starter; made with freshly grounds that you don''t want to know where they came from!', '2.95', 'coffee.png', 'd');
            
                DROP TABLE IF EXISTS `orderitems`;
                CREATE TABLE IF NOT EXISTS `orderitems` (
                  `order` int(11) NOT NULL,
                  `item` int(11) NOT NULL,
                  `quantity` int(11) NOT NULL,
                  PRIMARY KEY (`order`,`item`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                
               DROP TABLE IF EXISTS `orders`;
                CREATE TABLE IF NOT EXISTS `orders` (
                  `num` int(11) NOT NULL,
                  `date` datetime NOT NULL,
                  `status` varchar(1) NOT NULL,
                  `total` decimal(10,2) NOT NULL,
                  PRIMARY KEY (`num`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                
            ";  
        
        
        $this->install_model->use_sql_string($sql);

        /*  tried to update config.php file.
         * if ( ! $this->config->config_update(array('install'=>"locked")))
        {
            show_error('could not update config file');
            echo 'did not do the config file update';
        }
        else 
        {
            echo ' I did the config update';
        } */
 
		return TRUE;
    }

    
    
}