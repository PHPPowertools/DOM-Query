# DOM Query Component

***[PHPPowertools](https://github.com/PHPPowertools)*** is a web application framework for PHP >= 5.4.

***[PHPPowertools/DOM-Query](https://github.com/PHPPowertools/DOM-Query)*** is the first component of the ***PHPPowertools*** that has been released to the public.

The purpose of this component is to provide a ***[jQuery](http://jquery.com/)***-like interface for crawling XML and HTML documents. Under the hood, it uses ***[symfony/CssSelector](https://github.com/symfony/CssSelector)*** for converting ***[CSS selectors](http://www.w3.org/TR/CSS/)*** to ***[XPath queries](http://www.w3.org/TR/xpath/)***

-----

##### The jQuery way :
```js
// Find the elements that match selector 'div.foo'
$s = $('div.foo');

// Pass an element object (DOM Element)
$s = $(document.body);

// Pass a jQuery object
$s = $($('p + p'));
```
##### The DOM-Query way :
```php
namespace App;
use \PowerTools\DOM_Query;

// Get file content
$htmlcode = file_get_contents('https://github.com');

// Create a new DOM_Query instance, using a string as a source
$H = new DOM_Query($htmlcode);

// Create a new DOM_Query instance, using an existing DOM_Query instance as a source
$H = new DOM_Query($H->select('body'));

// Find the elements that match selector 'div.foo'
$s = $H->select('div.foo');

// Pass an element object (DOM Element)
$s = $H->select($documentBody);

// Pass a DOM Query instance
$s = $H->select($H->select('p + p'));
```
-----

##### Example use :

```php
// Select the body tag
$body = $H->select('body');

// Combine different classes as one selector to get all site blocks
$siteblocks = $body->select('.site-header, .masthead, .site-body, .site-footer');

// Nest your methods just like you would with jQuery
$siteblocks->select('button')->add('span')->addClass('icon icon-printer');

// Use a lambda function to set the text of all site blocks
$siteblocks->text(function($i, $val) {
    return $i . " - " . $val->attr('class');
});

// Append the following HTML to all site blocks
$siteblocks->append('<div class="site-center"></div>');

// Use a descendant selector to select the site's footer
$sitefooter = $body->select('.site-footer > .site-center');

// Set some attributes for the site's footer
$sitefooter->attr(array('id' => 'aweeesome', 'data-val' => 'see'));

// Use a lambda function to set the attributes of all site blocks
$siteblocks->attr('data-val', function($i, $val) {
    return $i . " - " . $val->attr('class') . " - photo by Kelly Clark";
});

// Select the parent of the site's footer
$sitefooterparent = $sitefooter->parent();

// Remove the class of all i-tags within the site's footer's parent
$sitefooterparent->select('i')->removeAttr('class');

// Wrap the site's footer within two nex selectors
$sitefooter->wrap('<section><div class="footer-wrapper"></div></section>');

[...]
```

-----

##### Supported methods :

- [x] [$](http://api.jquery.com/jQuery/) *(1)*
- [x] [$.parseHTML](http://api.jquery.com/jQuery.parseHTML/)
- [x] [$.parseXML](http://api.jquery.com/jQuery.parseXML/)
- [x] [$.parseJSON](http://api.jquery.com/jQuery.parseJSON/)
- [x] [$selection.add](http://api.jquery.com/add/)
- [x] [$selection.addClass](http://api.jquery.com/addClass/)
- [x] [$selection.after](http://api.jquery.com/after/)
- [x] [$selection.append](http://api.jquery.com/append/)
- [x] [$selection.attr](http://api.jquery.com/attr/)
- [x] [$selection.before](http://api.jquery.com/before/)
- [x] [$selection.children](http://api.jquery.com/children/)
- [x] [$selection.closest](http://api.jquery.com/closest/)
- [x] [$selection.contents](http://api.jquery.com/contents/)
- [x] [$selection.detach](http://api.jquery.com/detach/)
- [x] [$selection.each](http://api.jquery.com/each/)
- [x] [$selection.eq](http://api.jquery.com/eq/)
- [x] [$selection.empty](http://api.jquery.com/empty/) *(2)*
- [x] [$selection.find](http://api.jquery.com/find/)
- [x] [$selection.first](http://api.jquery.com/first/)
- [x] [$selection.get](http://api.jquery.com/get/)
- [x] [$selection.insertAfter](http://api.jquery.com/insertAfter/)
- [x] [$selection.insertBefore](http://api.jquery.com/insertBefore/)
- [x] [$selection.last](http://api.jquery.com/last/)
- [x] [$selection.parent](http://api.jquery.com/parent/)
- [x] [$selection.parents](http://api.jquery.com/parents/)
- [x] [$selection.remove](http://api.jquery.com/remove/)
- [x] [$selection.removeAttr](http://api.jquery.com/removeAttr/)
- [x] [$selection.removeClass](http://api.jquery.com/removeClass/)
- [x] [$selection.text](http://api.jquery.com/text/)
- [x] [$selection.wrap](http://api.jquery.com/wrap/)

-----

1. *Renamed 'select', for obvious reasons*
2. *Renamed 'void', since 'empty' is a reserved word in PHP*

-----

##### Author

| [![twitter/johnslegers](https://en.gravatar.com/avatar/bf4cc94221382810233575862875e687?s=70)](http://twitter.com/johnslegers "Follow @johnslegers on Twitter") |
|---|
| [John slegers](http://www.johnslegers.com/) |
