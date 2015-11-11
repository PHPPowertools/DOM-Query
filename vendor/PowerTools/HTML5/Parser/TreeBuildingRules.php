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
 *               COMPONENT : HTML5 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               DESCRIPTION :
 *
 *               A library for easy HTML5 parsing
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               REQUIREMENTS :
 *
 *               PHP version 5.4+
 *               PSR-0 compatibility
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *               CREDITS : 
 *
 *               This library started out as a fork of Masterminds/html5-php
 *
 *               Contributors of that Masterminds/html5-php :
 *               ---------------------------------------------
 *               Matt Butcher [technosophos]
 *               Matt Farina  [mattfarina]
 *               Asmir Mustafic [goetas]
 *               Edward Z. Yang [ezyang]
 *               Geoffrey Sneddon [gsnedders]
 *               Kukhar Vasily [ngreduce]
 *               Rune Christensen [MrElectronic]
 *               Mišo Belica [miso-belica]
 *               Asmir Mustafic [goetas]
 *               KITAITI Makoto [KitaitiMakoto]
 *               Jacob Floyd [cognifloyd]
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
 *  @category  HTML5 parsing
 *  @package   HTML5
 *  @author    John Slegers
 *  @copyright MMXIV John Slegers
 *  @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *  @link      https://github.com/jslegers
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

namespace PowerTools;

/**
 * Handles special-case rules for the DOM tree builder.
 *
 * Many tags have special rules that need to be accomodated on an
 * individual basis. This class handles those rules.
 *
 * See section 8.1.2.4 of the spec.
 *
 * @todo - colgroup and col special behaviors
 *       - body and head special behaviors
 */
class HTML5_Parser_TreeBuildingRules {

    protected static $tags = array(
        'li' => 1,
        'dd' => 1,
        'dt' => 1,
        'rt' => 1,
        'rp' => 1,
        'tr' => 1,
        'th' => 1,
        'td' => 1,
        'thead' => 1,
        'tfoot' => 1,
        'tbody' => 1,
        'table' => 1,
        'optgroup' => 1,
        'option' => 1
    );

    /**
     * Build a new rules engine.
     *
     * @param \DOMDocument $doc
     *            The DOM document to use for evaluation and modification.
     */
    public function __construct($doc) {
        $this->doc = $doc;
    }

    /**
     * Returns true if the given tagname has special processing rules.
     */
    public function hasRules($tagname) {
        return isset(static::$tags[$tagname]);
    }

    /**
     * Evaluate the rule for the current tag name.
     *
     * This may modify the existing DOM.
     *
     * @return \DOMElement The new Current DOM element.
     */
    public function evaluate($new, $current) {
        switch ($new->tagName) {
            case 'li':
                return $this->handleLI($new, $current);
            case 'dt':
            case 'dd':
                return $this->handleDT($new, $current);
            case 'rt':
            case 'rp':
                return $this->handleRT($new, $current);
            case 'optgroup':
                return $this->closeIfCurrentMatches($new, $current, array(
                            'optgroup'
                ));
            case 'option':
                return $this->closeIfCurrentMatches($new, $current, array(
                            'option',
                            'optgroup'
                ));
            case 'tr':
                return $this->closeIfCurrentMatches($new, $current, array(
                            'tr'
                ));
            case 'td':
            case 'th':
                return $this->closeIfCurrentMatches($new, $current, array(
                            'th',
                            'td'
                ));
            case 'tbody':
            case 'thead':
            case 'tfoot':
            case 'table': // Spec isn't explicit about this, but it's necessary.

                return $this->closeIfCurrentMatches($new, $current, array(
                            'thead',
                            'tfoot',
                            'tbody'
                ));
        }

        return $current;
    }

    protected function handleLI($ele, $current) {
        return $this->closeIfCurrentMatches($ele, $current, array(
                    'li'
        ));
    }

    protected function handleDT($ele, $current) {
        return $this->closeIfCurrentMatches($ele, $current, array(
                    'dt',
                    'dd'
        ));
    }

    protected function handleRT($ele, $current) {
        return $this->closeIfCurrentMatches($ele, $current, array(
                    'rt',
                    'rp'
        ));
    }

    protected function closeIfCurrentMatches($ele, $current, $match) {
        $tname = $current->tagName;
        if (in_array($current->tagName, $match)) {
            $current->parentNode->appendChild($ele);
        } else {
            $current->appendChild($ele);
        }

        return $ele;
    }

}
