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

/**
 * @file
 * The interface definition for Rules to generate output.
 */

namespace PowerTools;

/**
 * To create a new rule set for writing output the RulesInterface needs to be
 * implemented.
 * The resulting class can be specified in the options with the
 * key of rules.
 *
 * For an example implementation see use HTML5_Serializer_OutputRules
 */
interface HTML5_Serializer_RulesInterface {

    /**
     * The class constructor.
     *
     * Note, before the rules can be used a traverser must be registered.
     *
     * @param mixed $output
     *            The output stream to write output to.
     * @param array $options
     *            An array of options.
     */
    public function __construct($output, $options = array());

    /**
     * Register the traverser used in but the rules.
     *
     * Note, only one traverser can be used by the rules.
     *
     * @param use HTML5_Serializer_Traverser As Traverser $traverser
     *            The traverser used in the rules.
     * @return HTML5_Serializer_RulesInterface As RulesInterface $this for the current object.
     */
    public function setTraverser(HTML5_Serializer_Traverser $traverser);

    /**
     * Write a document element (\DOMDocument).
     *
     * Instead of returning the result write it to the output stream ($output)
     * that was passed into the constructor.
     *
     * @param \DOMDocument $dom
     */
    public function document($dom);

    /**
     * Write an element.
     *
     * Instead of returning the result write it to the output stream ($output)
     * that was passed into the constructor.
     *
     * @param mixed $ele
     */
    public function element($ele);

    /**
     * Write a text node.
     *
     * Instead of returning the result write it to the output stream ($output)
     * that was passed into the constructor.
     *
     * @param mixed $ele
     */
    public function text($ele);

    /**
     * Write a CDATA node.
     *
     * Instead of returning the result write it to the output stream ($output)
     * that was passed into the constructor.
     *
     * @param mixed $ele
     */
    public function cdata($ele);

    /**
     * Write a comment node.
     *
     * Instead of returning the result write it to the output stream ($output)
     * that was passed into the constructor.
     *
     * @param mixed $ele
     */
    public function comment($ele);

    /**
     * Write a processor instruction.
     *
     * To learn about processor instructions see HTML5_InstructionProcessor
     *
     * Instead of returning the result write it to the output stream ($output)
     * that was passed into the constructor.
     *
     * @param mixed $ele
     */
    public function processorInstruction($ele);
}
