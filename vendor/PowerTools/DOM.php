<?php

/* !
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *               PACKAGE : PHP POWERTOOLS
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *               COMPONENT : DOM QUERY 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               DESCRIPTION :
 *
 *               A library for easy selection, crawling and
 *               modification of HTML and XML.
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               REQUIREMENTS :
 *
 *               PHP version 5.3+
 *               PSR-0 compatibility
 *
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               LICENSE :
 *
 * LICENSE: Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *  @category  Autoloader
 *  @package   /
 *  @author    John Slegers
 *  @copyright MMXIV John Slegers
 *  @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *  @link      https://github.com/jslegers
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

namespace PowerTools;

use \Symfony\Component\CssSelector\CssSelector as CssSelector;

class DOM extends \DOMDocument {

    protected $_isHTML = false;

    public function __construct($data = false, $version = null, $encoding = null) {
        parent::__construct($version, $encoding);
        $data = trim($data);
        if ($data && $data != '') {
            if ($this->_isHTML) {
                @$this->loadHTML($data);
            } else {
                @$this->loadXML($data);
            }
        }
    }

    public function querySelectorAll($selector, $contextnode = null) {
        if ($this->_isHTML) {
            CssSelector::enableHtmlExtension();
        } else {
            CssSelector::disableHtmlExtension();
        }
        $xpath = new \DOMXpath($this);
        return $xpath->query(CssSelector::toXPath($selector, 'descendant::'), $contextnode);
    }

    public function querySelector($selector, $contextnode = null) {
        $items = $this->querySelectorAll($selector, $contextnode);
        if ($items->length > 0) {
            return $items->item(0);
        }
        return null;
    }

    public function loadXMLFile($filename, $options = 0) {
        return parent::load($filename, $options);
    }

    public function saveXMLFile($filename, $options = null) {
        return parent::save($filename, $options);
    }

    public function save($filename, $options = null) {
        if ($this->_isHTML) {
            return parent::saveHTMLFile($filename);
        }
        return $this->saveXMLFile($filename, $options);
    }

    public function load($filename, $options = null) {
        if ($this->_isHTML) {
            return parent::loadHTMLFile($filename);
        }
        return $this->loadXMLFile($filename, $options);
    }

}
