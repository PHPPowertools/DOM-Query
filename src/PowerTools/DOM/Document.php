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
 *               modification of DOM_ and XML.
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               REQUIREMENTS :
 *
 *               PHP version 5.4+
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
 *  @category  DOM Selection
 *  @package   DOM-Query
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

class DOM_Document extends \DOMDocument {

    public function __construct($data = false, $doctype = 'html', $encoding = 'UTF-8', $version = '1.0') {
        parent::__construct($version, $encoding);
        if ($doctype && $doctype === 'html') {
            @$this->loadHTML($data);
        } else {
            @$this->loadXML($data);
        }
    }

    public function querySelectorAll($selector, $contextnode = null) {
        if (isset($this->doctype->name) && $this->doctype->name == 'html') {
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

    public function loadHTMLFile($filename, $options = 0) {
        $this->loadHTML(file_get_contents($filename), $options);
    }

    public function loadHTML($source, $options = 0) {
        if ($source && $source != '') {
            $data = trim($source);
            $html5 = new HTML5(array('targetDocument' => $this, 'disableHtmlNsInDom' => true));
            $data_start = mb_substr($data, 0, 10);
            if (strpos($data_start, '<!DOCTYPE ') === 0 || strpos($data_start, '<html>') === 0) {
                $html5->loadHTML($data);
            } else {
                @$this->loadHTML('<!DOCTYPE html><html><head><meta charset="' . $encoding . '" /></head><body></body></html>');
                $t = $html5->loadHTMLFragment($data);
                $docbody = $this->getElementsByTagName('body')->item(0);
                while ($t->hasChildNodes()) {
                    $docbody->appendChild($t->firstChild);
                }
            }
        }
    }

    public function saveHTMLFile($filename, $node = null) {
        return file_put_contents($filename, $this->saveHTML($node));
    }

    public function saveHTML($node = null, $options = null) {
        $node = ($node === null) ? $this : $node;
        $options = ($options === null) ? array() : $options;
        $html5 = new HTML5($options);
        return $html5->saveHTML($node);
    }

    public function loadXMLFile($filename, $options = 0) {
        return parent::load($filename, $options);
    }

    public function loadXML($source, $options = 0) {
        if ($source && $source != '') {
            $data = trim($source);
            parent::loadXML('<!DOCTYPE ' . $doctype . '><' . $doctype . '></' . $doctype . '>');
            @parent::loadXML($data);
        }
    }

    public function saveXMLFile($filename, $options = null) {
        return parent::save($filename, $options);
    }

    public function save($filename, $options = null) {
        if (isset($this->doctype->name) && $this->doctype->name == 'html') {
            return $this->saveHTMLFile($filename, $options);
        } else {
            return $this->saveXMLFile($filename, $options);
        }
    }

    public function load($filename, $options = null) {
        if (isset($this->doctype->name) && $this->doctype->name == 'html') {
            return $this->loadHTMLFile($filename, $options);
        } else {
            return $this->loadXMLFile($filename, $options);
        }
    }

}
