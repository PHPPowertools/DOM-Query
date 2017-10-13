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
 *  @category  DOM Crawling
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

class DOM_Query {

    public $DOM;
    public $nodes = null;
    public $isHtml = true;

    public static function loadHTML($source) {
        return new static($source, true);
    }

    public static function loadXML($source) {
        return new static($source, false);
    }

    public function __construct($source, $isHtml = true) {
        if (is_string($source)) {
            if ($isHtml) {
                $this->DOM = new DOM_HTML($source);
                $this->isHtml = true;
            } else {
                $this->DOM = new DOM_XML($source);
                $this->isHtml = false;
            }
        } else {
            $this->DOM = $source;
            if (isset($source->doctype->name) && $source->doctype->name == 'html') {
                if ($source->doctype->name == 'html') {
                    $this->isHtml = true;
                } else {
                    $this->isHtml = false;
                }
            } else {
                $this->isHtml = $isHtml;
            }
        }
        $this->nodes = array($this->DOM);
    }

    public function __toString() {
        if ($this->nodes === array($this->DOM)) {
            return $this->DOM->saveHTML();
        } else
        if ($this->nodes === array(null)) {
            return '';
        } else {
            $newdoc = new DOM_HTML();
            foreach ($this->nodes as $value) {
                $newdoc->appendChild($newdoc->importNode($value->cloneNode(TRUE), TRUE));
            }
            return $newdoc->saveHTML();
        }
    }

    public function _each($function) {
        $select = $this->select($this->nodes);
        for ($i = count($select->nodes) - 1; $i > -1; $i--) {
            $function($i, $this->select($select->nodes[$i]));
        }
        return $select;
    }

    protected function _getValue($v, $key) {
        return $v->$key;
    }

    protected function _setValue($v, $key, $value) {
        $v->$key = $value;
        return $this;
    }

    protected function _select($v, $key, $value) {
        $selected = $this->DOM->querySelectorAll($key, $v);
        foreach ($selected as $v) {
            if ($v !== NULL && !DOM_Helper::containsElement($value->nodes, $v)) {
                array_push($value->nodes, $v);
            }
        }
        return $this;
    }

    protected function _runGetter($obj = null, $getter, $key, $getFirstOnly = true) {
        $results = array();
        foreach ($this->nodes as $v) {
            $node = $obj === null ? $v->{$getter}($key) : $obj->{$getter}($v, $key);
            if (DOM_Helper::getType($node) === 'DOMNodeList') {
                foreach ($node as $node) {
                    if ($node !== NULL && !DOM_Helper::containsElement($results, $node)) {
                        array_push($results, $node);
                        if ($getFirstOnly)
                            return $results[0];
                    }
                }
            } else {
                if ($node !== NULL && !DOM_Helper::containsElement($results, $node)) {
                    array_push($results, $node);
                    if ($getFirstOnly)
                        return $results[0];
                }
            }
        }
        return $results;
    }

    protected function _runSetter($obj = null, $setter, $key, $value = null) {
        $i = 0;
        foreach ($this->nodes as $v) {
            $val = $value;
            if (is_callable($value)) {
                $val = $val($i, $this->select($v));
            }
            $obj === null ? $v->{$setter}($key, $val) : $obj->{$setter}($v, $key, $val);
            $i++;
        }
        return $this;
    }

    protected function _runGetterOrSetter($obj = null, $getter, $setter, $key, $value = false, $getFirstOnly = true) {
        if ($value) {
            return $this->_runSetter($obj, $setter, $key, $value);
        } elseif (is_array($key)) {
            foreach ($key as $key => $value) {
                $this->_runSetter($obj, $setter, $key, $value);
            }
            return $this;
        } else {
            return $this->_runGetter($obj, $getter, $key, $getFirstOnly);
        }
    }

    protected function _append($children, $clone = true) {
        $indexNotClone = $clone ? -1 : count($this->nodes) - 1;
        foreach ($children as $child) {
            foreach ($this->nodes as $i => $v) {
                if (is_callable($child)) {
                    $child = $child($i, $this->select($v));
                }
                if ($i == $indexNotClone) {
                    $v->appendChild($child);
                } else {
                    $v->appendChild($child->cloneNode(TRUE));
                }
            }
        }
        return $this;
    }

    protected function _before($children, $clone = true) {
        $indexNotClone = $clone ? -1 : count($this->nodes) - 1;
        foreach ($children as $child) {
            foreach ($this->nodes as $i => $v) {
                if (is_callable($child)) {
                    $child = $child($i, $this->select($v));
                }
                if ($i == $indexNotClone) {
                    $v->parentNode->insertBefore($child, $v);
                } else {
                    $v->parentNode->insertBefore($child->cloneNode(TRUE), $v);
                }
            }
        }
        return $this;
    }

    protected function _after($children, $clone = true) {
        $indexNotClone = $clone ? -1 : count($this->nodes) - 1;
        foreach ($children as $child) {
            foreach ($this->nodes as $i => $v) {
                if (is_callable($child)) {
                    $child = $child($i, $this->select($v));
                }
                if ($i == $indexNotClone) {
                    $v->parentNode->insertBefore($child, $v->nextSibling);
                } else {
                    $v->parentNode->insertBefore($child->cloneNode(TRUE), $v->nextSibling);
                }
            }
        }
        return $this;
    }

    protected function _importNodes($object) {
        if ($object->DOM === $this->DOM) {
            return $object->nodes;
        } else {
            $nodes = array();
            foreach ($object->nodes as $node) {
                if ($node->nodeName === '#document') {
                    if ($this->isHtml) {
                        $nodes = array_merge($nodes, $this->parseHTML($node));
                    } else {
                        $nodes = array_merge($nodes, $this->parseXML($node));
                    }
                } else {
                    array_push($nodes, $this->DOM->importNode($node, true));
                }
            }
            return $nodes;
        }
    }

    public function parseHTML($newdoc) {
        // http://api.jquery.com/jQuery.parseHTML/
        if (is_string($newdoc)) {
            $newdoc = new DOM_HTML($newdoc);
        }
        $vals = $newdoc->querySelector('body')->childNodes;
        $ret = array();
        foreach ($vals as $i => $c) {
            $ret[$i] = $this->DOM->importNode($c, TRUE);
        }
        return $ret;
    }

    public function parseJSON($string) {
        // http://api.jquery.com/jQuery.parseJSON/
        return json_decode($string, true);
    }

    public function parseXML($string) {
        // http://api.jquery.com/jQuery.parseXML/
        $newdoc = new DOM_XML('<root>' . $string . '</root>');
        $vals = $newdoc->childNodes->item(0)->childNodes;
        $ret = array();
        foreach ($vals as $i => $c) {
            $ret[$i] = $this->DOM->importNode($c, TRUE);
        }
        return $ret;
    }

    public function text($value = false) {
        // http://api.jquery.com/text/
        $result = $this->_runGetterOrSetter($this, '_getValue', '_setValue', 'nodeValue', $value, false);
        if (!$value) {
            return implode('', $result);
        }
        return $result;
    }

    public function attr($key, $value = false) {
        // http://api.jquery.com/attr/
        return $this->_runGetterOrSetter(null, 'getAttribute', 'setAttribute', $key, $value, true);
    }

    public function is($restriction = '') {
        // http://api.jquery.com/is/

        $is = false;

        // Return if the current query has no results.
        if (count($this->nodes) === 0) {
            return $is;
        }

        // If more than one argument was passed, check if any of them are
        // DOMElements and if so, create an array of the ones that are.
        // http://api.jquery.com/is/#is-elements
        $arguments = func_get_args();

        foreach ($arguments as &$argument) {
            if (DOM_Helper::getType($argument) === 'DOMElement') {
                $nodes[] = &$argument;
            }
        }

        if (count($nodes) > 0) {
            $restriction = &$nodes;
        }

        switch (DOM_Helper::getType($restriction)) {
            case 'DOMElement':
                // If we only have one node passed, wrap it in an array to
                // reduce duplicate code.
                $restriction = array(&$restriction);

                break;

            case 'DOM_Query':
                // If we're passed a DOM_Query, grab the array of nodes.
                $restriction = &$restriction->nodes;

                break;
        }

        switch (DOM_Helper::getType($restriction)) {
            // If the restriction is a string, it's a selector.
            // http://api.jquery.com/is/#is-selector
            case 'String':

                // Return on empty selector string.
                if (mb_strlen(trim($restriction)) === 0) {
                    return $is;
                }

                // Loop through all nodes found in the current query.
                foreach ($this->nodes as &$thisNode) {
                    // Loop through all of the children of the parent of the
                    // current node we're testing, filtered by the selector.
                    foreach (
                        $this->select($thisNode)
                            ->parent()->children($restriction)->nodes
                        as &$parentChildNode
                    ) {
                        if (
                            $thisNode->isSameNode($parentChildNode)
                        ) {
                            // If one of the nodes is the current query node,
                            // say we've found a match and break out of both
                            // loops, to not run needlessly.

                            $is = true;

                            break 2;
                        }
                    }
                }

                break;

            case 'Array':
            case 'DOMNodeList':
                // If the restriction is an array or DOMNodeList, iterate over
                // it, breaking when one matches a node in our current query.
                foreach ($restriction as &$node) {
                    if (DOM_Helper::containsElement($this->nodes, $node)) {
                        $is = true;

                        break;
                    }
                }

                break;

            case 'Callable':
                // If the restriction is a function, run it on every node in
                // the query, breaking when one returns true.
                foreach ($this->nodes as $i => &$node) {
                    if ($restriction($i, $node) === true) {
                        $is = true;

                        break;
                    }
                }

                break;
        }

        return $is;
    }

    public function add($selector) {
        // http://api.jquery.com/add/
        $clone = new DOM_Query($this->DOM, $this->isHtml);
        $clone = $clone->select($selector);
        $this->nodes = DOM_Helper::merge($this->nodes, $clone->nodes);
        return $this;
    }

    public function addClass($newclasses) {
        // http://api.jquery.com/addClass/
        $this->attr('class', function($i, $val) use ($newclasses) {
            $oldclasses = $val->attr('class');
            if ($oldclasses === '') {
                $oldclasses = array();
            } else {
                $oldclasses = explode(' ', $oldclasses);
            }
            if (DOM_Helper::getType($newclasses) === 'Callable') {
                $newclasses = $newclasses($i, $val);
            } else {
                $newclasses = explode(' ', $newclasses);
            }
            foreach ($newclasses as $newclass) {
                if (!in_array($newclass, $oldclasses)) {
                    // This is faster than calling array_push():
                    // http://php.net/manual/en/function.array-push.php
                    $oldclasses[] = $newclass;
                }
            }
            return implode(' ', $oldclasses);
        });
        return $this;
    }

    public function hasClass($class) {
        // http://api.jquery.com/hasClass/

        $classFound = false;

        $this->each(function($i, $element) use ($class, &$classFound) {
            if (in_array(
                    $class,
                    explode(' ', $element->attr('class'))
            )) {
                $classFound = true;
            }
        });

        return $classFound;
    }

    public function removeAttr($key) {
        // http://api.jquery.com/removeAttr/
        $keys = explode(' ', $key);
        foreach ($keys as $key) {
            $this->_runGetter(null, 'removeAttribute', $key, false);
        }
        return $this;
    }

    public function detach($selector = false) {
        // http://api.jquery.com/detach/
        if ($selector) {
            return $this->select($selector)->detach();
        }
        foreach ($this->nodes as $v) {
            $v->parentNode->removeChild($v);
        }
        return $this;
    }

    public function contents() {
        // http://api.jquery.com/contents/
        $s = $this->select($this->_runGetter($this, '_getValue', 'childNodes', false));
        return $s;
    }

    public function children($selector = false) {
        // http://api.jquery.com/children/

        if ($selector) {
            $restriction = $this->select($selector)->nodes;
        }
        $contents = $this->contents();
        for ($i = count($contents->nodes) - 1; $i > -1; $i--) {
            if ($contents->nodes[$i]->nodeType === 3) {
                unset($contents->nodes[$i]);
            } elseif ($selector && !DOM_Helper::containsElement($restriction, $contents->nodes[$i])) {
                unset($contents->nodes[$i]);
            }
        }
        return $contents;
    }

    public function find($selector = false) {
        // http://api.jquery.com/find/
        $descendants = array();
        if ($selector) {
            $restriction = $this->select($selector)->nodes;
        }
        foreach ($this->nodes as $node) {
            $children = $this->select($node)->children();
            if (count($children->nodes) > 0) {
                $grandchildren = $children->find($selector);
                if (count($grandchildren->nodes) > 0) {
                    $children->nodes = DOM_Helper::merge($children->nodes, $grandchildren->nodes);
                }
                for ($i = count($children->nodes) - 1; $i > -1; $i--) {
                    if ($selector && !DOM_Helper::containsElement($restriction, $children->nodes[$i])) {
                        unset($children->nodes[$i]);
                    }
                }
                $descendants = DOM_Helper::merge($descendants, $children->nodes);
            }
        }
        return $this->select($descendants);
    }

    public function each($function) {
        return $this->_each($function);
    }

    public function closest($selector = false) {
        // http://api.jquery.com/closest/
    }

    public function void() {
        // http://api.jquery.com/empty/
        $this->contents()->remove();
        return $this;
    }

    public function remove($selector = false) {
        // http://api.jquery.com/remove/
        $this->detach($selector);
        $this->nodes = array();
        return $this;
    }

    public function removeClass($classestoremove) {
        // http://api.jquery.com/removeClass/
        $this->attr('class', function($i, $val) use ($classestoremove) {
            $oldclasses = $val->attr('class');
            if ($oldclasses === '') {
                return '';
            }
            $oldclasses = explode(' ', $oldclasses);
            if (DOM_Helper::getType($newclasses) === 'Callable') {
                $classestoremove = $classestoremove($i, $val);
            } else {
                $classestoremove = explode(' ', $classestoremove);
            }
            for ($i = count($oldclasses) - 1; $i > -1; $i--) {
                if (in_array($oldclasses[$i], $classestoremove)) {
                    unset($oldclasses[$i]);
                }
            }
            return implode(' ', $oldclasses);
        });
        return $this;
    }

    public function select($selector) {
        $wrapper = new DOM_Query($this->DOM, $this->isHtml);

        switch (DOM_Helper::getType($selector)) {
            case 'String':
                if ($selector[0] === '<') {
                    $wrapper->nodes = $wrapper->parseHTML($selector);
                } else {
                    $wrapper->nodes = array();
                    $this->_runSetter($this, '_select', $selector, $wrapper);
                }
                break;
            case 'Array':
                $wrapper->nodes = $selector;
                break;
            case 'DOM_Query':
                $wrapper->nodes = $this->_importNodes($selector);
                break;
            default:
                $wrapper->nodes = array($selector);
        }
        return $wrapper;
    }

    public function first() {
        // http://api.jquery.com/first/
        if (count($this->nodes) > 0) {
            return $this->select($this->nodes[0]);
        }
        return $this;
    }

    public function last() {
        // http://api.jquery.com/last/
        $count = count($this->nodes);
        if ($count > 0) {
            return $this->select($this->nodes[$count - 1]);
        }
        return $this;
    }

    public function append() {
        // http://api.jquery.com/append/
        $values = func_get_args();
        foreach ($values as $value) {
            switch (DOM_Helper::getType($value)) {
                case 'String':
                    $this->_append($this->parseHTML($value));
                    break;
                case 'Array':
                    $this->_append($value, false);
                    break;
                case 'DOM_Query':
                    $this->_append($this->_importNodes($value), false);
                    break;
                case 'Invalid':
                    break;
                default:
                    $this->_append(array($value), false);
            }
        }
        return $this;
    }

    public function before() {
        // http://api.jquery.com/before/
        $values = func_get_args();
        foreach ($values as $value) {
            switch (DOM_Helper::getType($value)) {
                case 'String':
                    $this->_before($this->parseHTML($value));
                    break;
                case 'Array':
                    $this->_before($value, false);
                    break;
                case 'DOM_Query':
                    $this->_before($this->_importNodes($value), false);
                    break;
                default:
                    $this->_before(array($value), false);
            }
        }
        return $this;
    }

    public function after() {
        // http://api.jquery.com/after/
        $values = func_get_args();
        foreach ($values as $value) {
            switch (DOM_Helper::getType($value)) {
                case 'String':
                    $this->_after($this->parseHTML($value));
                    break;
                case 'Array':
                    $this->_after($value, false);
                    break;
                case 'DOM_Query':
                    $this->_after($this->_importNodes($value), false);
                    break;
                default:
                    $this->_after(array($value), false);
            }
        }
        return $this;
    }

    public function insertBefore($target) {
        // http://api.jquery.com/insertBefore/
        $wrapper = new DOM_Query($this->DOM, $this->isHtml);
        return $wrapper->select($target)->before($this->nodes);
    }

    public function insertAfter($target) {
        // http://api.jquery.com/insertAfter/
        $wrapper = new DOM_Query($this->DOM, $this->isHtml);
        return $wrapper->select($target)->after($this->nodes);
    }

    public function get($index = false) {
        // http://api.jquery.com/get/
        if (is_int($index)) {
            if ($index < 0) {
                return $this->nodes[count($this->nodes) + $index];
            }
            return $this->nodes[$index];
        }
        return $this->nodes;
    }

    public function eq($index = false) {
        // http://api.jquery.com/eq/
        return $this->select($this->get($index));
    }

    public function wrap($wrapper) {
        // http://api.jquery.com/wrap/
        switch (DOM_Helper::getType($wrapper)) {
            case 'String':
                if ($wrapper[0] === '<') {
                    $wrapper = $this->parseHTML($wrapper);
                }
                $wrapper = $this->select($wrapper)->nodes[0];
                break;
            case 'Array':
                $wrapper = $wrapper[0];
                break;
            case 'DOM_Query':
                $nodes = $this->_importNodes($wrapper);
                $wrapper = $nodes[0];
                break;
        }
        foreach ($this->nodes as $i => $node) {
            $new = $wrapper->cloneNode(TRUE);
            $firstchild = $this->select($new);
            while (true) {
                $firstchild = $firstchild->contents()->nodes[0];
                if ($firstchild === NULL) {
                    break;
                } else {
                    $innerchild = $firstchild;
                    $firstchild = $this->select($firstchild);
                }
            }
            if ($innerchild !== NULL) {
                $innerchild->appendChild($node);
            }
            $nextSibling = $node->nextSibling;
            $parent = $node->parentNode;
            $parent->insertBefore($new, $nextSibling);
        }
        return $this;
    }

    public function render($echo = true) {
        if (!$echo) {
            Buffer::push();
            echo $this;
            return Buffer::pop();
        }
        echo $this;
    }

    public function parents($selector = false) {
        // http://api.jquery.com/parents/
        $ancestors = array();
        foreach ($this->nodes as $node) {
            $parent = $this->select($node)->parent($selector);
            if ($parent->nodes[0] !== $this->DOM) {
                $nodes = $this->select($parent)->parents()->nodes;
                array_unshift($nodes, $parent->nodes[0]);
                $ancestors = DOM_Helper::merge($ancestors, $nodes);
            }
        }
        return $this->select($ancestors);
    }

    public function parent($selector = false) {
        // http://api.jquery.com/parent/
        return $this->select($this->_runGetter($this, '_getValue', 'parentNode', false));
    }
}
