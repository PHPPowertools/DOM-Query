<?php

namespace PowerTools;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Query
 *
 * @author john
 */
class DOM_Helper {

    public static function containsElement(&$dataset, $node) {
        if (static::getType($node) === 'DOMElement') {
            foreach ($dataset as $value) {
                if ($node->isSameNode($value)) {
                    return true;
                }
            }
        } else {
            return false;
        }
        return false;
    }

    public static function merge(&$dataset1, &$dataset2) {
        $result = $dataset1;
        foreach ($dataset2 as $value) {
            if (!DOM_Helper::containsElement($result, $value)) {
                array_push($result, $value);
            }
        }
        return $result;
    }

    public static function getType($test) {
        if (is_string($test)) {
            return 'String';
        }
        if (is_array($test)) {
            return 'Array';
        }
        if (is_callable($test)) {
            return 'Callable';
        }
        if (is_a($test, 'PowerTools\DOM_Query')) {
            return 'DOM_Query';
        }
        if (is_a($test, '\DOMElement')) {
            return 'DOMElement';
        }
        if (is_a($test, '\DOMNodeList')) {
            return 'DOMNodeList';
        }
        if (is_a($test, '\DOMDocument')) {
            return 'DOMDocument';
        }
        return 'Invalid';
    }

}
