<?php

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
