# DOM Query Component

***[PHPPowertools](https://github.com/PHPPowertools)*** is a web application framework for PHP > 5.3.

***[PHPPowertools/DOM-Query](https://github.com/PHPPowertools/DOM-Query)*** is the first component of the ***PHPPowertools*** that has been released to the public.

The purpose of this component is to provide a ***[jQuery](http://jquery.com/)***-like interface for crawling XML and HTML documents. Under the hood, it uses ***[symfony/CssSelector](https://github.com/symfony/CssSelector)*** for converting ***[CSS selectors](http://www.w3.org/TR/CSS/)*** to ***[XPath queries](http://www.w3.org/TR/xpath/)***



## Example use :

```php
// Select the body tag
$body = $H->select('body');

// Combine different classes as one selector to get all site blocks
$siteblocks = $body->select('.site-header, .masthead, .site-body, .site-footer');

// Nest your methods just like you would with jQuery
$siteblocks->select('button')->add('span')->addClass('icon icon-printer');

// Use a lambda function to set the text of all site blocks
$siteblocks->text(function( $i, $val) {
    return $i . " - " . $val->attr('class');
});

// Append the following HTML to all site blocks
$siteblocks->append('<div class="site-center"></div>');

// Use a descendant selector to select the site's footer
$sitefooter = $body->select('.site-footer > .site-center');

// Set attributes of the site's footer
$sitefooter->attr(array('id' => 'aweeesome', 'data-val' => 'see'));

// Use a lambda function to set the attributes of all site blocks
$siteblocks->attr('data-val', function( $i, $val) {
    return $i . " - " . $val->attr('class') . " - photo by Kelly Clark";
});

// Select the parents of the site's footer
$sitefooterparent = $sitefooter->parent();

// Remove the class of all i-tags within the site's footer's parent
$sitefooterparent->select('i')->removeAttr('class');

// Wrap the site's footer within two nex selectors
$sitefooter->wrap('<section><div class="footer-wrapper"></div></section>');

[...]
```



## Supported methods :

- [x] [add](http://api.jquery.com/add/)
- [x] [addClass](http://api.jquery.com/addClass/)
- [x] [after](http://api.jquery.com/after/)
- [x] [append](http://api.jquery.com/append/)
- [x] [attr](http://api.jquery.com/attr/)
- [x] [before](http://api.jquery.com/before/)
- [x] [children](http://api.jquery.com/children/)
- [x] [closest](http://api.jquery.com/closest/)
- [x] [contents](http://api.jquery.com/contents/)
- [x] [detach](http://api.jquery.com/detach/)
- [x] [each](http://api.jquery.com/each/)
- [x] [eq](http://api.jquery.com/eq/)
- [x] [empty](http://api.jquery.com/empty/) *(1)*
- [x] [find](http://api.jquery.com/find/)
- [x] [first](http://api.jquery.com/first/)
- [x] [get](http://api.jquery.com/get/)
- [x] [insertAfter](http://api.jquery.com/insertAfter/)
- [x] [insertBefore](http://api.jquery.com/insertBefore/)
- [x] [last](http://api.jquery.com/last/)
- [x] [parent](http://api.jquery.com/parent/)
- [x] [parents](http://api.jquery.com/parents/)
- [x] [remove](http://api.jquery.com/remove/)
- [x] [removeAttr](http://api.jquery.com/removeAttr/)
- [x] [removeClass](http://api.jquery.com/removeClass/)
- [x] [text](http://api.jquery.com/text/)
- [x] [wrap](http://api.jquery.com/wrap/)
- [x] [parseHTML](http://api.jquery.com/jQuery.parseHTML/)
- [x] [parseXML](http://api.jquery.com/jQuery.parseXML/)
- [x] [parseJSON](http://api.jquery.com/jQuery.parseJSON/)

*1. Since 'empty' is a reserved word in PHP, this method is named 'void'.*



## Author

| [![twitter/johnslegers](https://en.gravatar.com/avatar/bf4cc94221382810233575862875e687?s=70)](http://twitter.com/johnslegers "Follow @johnslegers on Twitter") |
|---|
| [John slegers](http://www.johnslegers.com/) |