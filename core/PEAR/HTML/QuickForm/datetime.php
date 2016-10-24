<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * HTML class for a datetime field
 * 
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    HTML
 * @package     HTML_QuickForm
 * @author      Thanh NV <vietthanh.nguyen@gmail.com>
 * @copyright   2001-2007 The PHP Group
 * @license     http://www.php.net/license/3_01.txt PHP License 3.01
 * @version     CVS: $Id: datetime.php,v 1.6 2007/05/29 18:34:36 avb Exp $
 * @link        http://pear.php.net/package/HTML_QuickForm
 */

/**
 * Base class for <input /> form elements
 */
require_once 'HTML/QuickForm/input.php';

/**
 * HTML class for a datetime field
 * 
 * @category    HTML
 * @package     HTML_QuickForm
 * @author      Adam Daniel <adaniel1@eesus.jnj.com>
 * @author      Bertrand Mansion <bmansion@mamasam.com>
 * @version     Release: 3.2.10
 * @since       1.0
 */
class HTML_QuickForm_datetime extends HTML_QuickForm_input
{
                
    // {{{ constructor

    /**
     * Class constructor
     * 
     * @param     string    $elementName    (optional)Input field name attribute
     * @param     string    $elementLabel   (optional)Input field label
     * @param     mixed     $attributes     (optional)Either a typical HTML attribute string 
     *                                      or an associative array
     * @since     1.0
     * @access    public
     * @return    void
     */
    function HTML_QuickForm_datetime($elementName=null, $elementLabel=null, $attributes=null)
    {
    	$attributes['readOnly'] = true;
    	$attributes['onClick'] = "displayCalendar(document.".HTML_QuickForm::$_formName.".{$elementName},'yyyy-mm-dd hh:ii',this,true)";
    	
        HTML_QuickForm_input::HTML_QuickForm_input($elementName, $elementLabel, $attributes);
        $this->_persistantFreeze = true;
        $this->setType('datetime');
    } //end constructor
        
    // }}}
    
} //end class HTML_QuickForm_datetime
?>

