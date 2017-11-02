<?php
namespace Admin\Helper;
use Zend\View\Helper\AbstractHelper;
class testHelper extends AbstractHelper {
    function __invoke() {
        return 'Ravi Ranjna';
    }
    
    function getName(){
        return 'Ravi Ranjan';
    }
    
}