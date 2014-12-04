DOM Query Component
=====================

The purpose of this component is to provide a jQuery-like interface for crawling XML and HTML documents.

Example use :

```php
// Select the body tag
$body = $H->select('body');

// Select all elements with at least one of the following for classes
$siteblocks = $body->select('.site-header, .masthead, .site-body, .site-footer');

// Set the text of selected elements to the return value of the lambda function
$siteblocks->text(function( $i, $val) {
    return $i . " - " . $val->attr('class');
});

// Appen the following HTML to the selected elements
$siteblocks->append('<div class="site-center"></div>');

// Set attributes of selected elements by assigning an array of values
$sitefooter->attr(array('id' => 'aweeesome', 'data-val' => 'see'));

// Set the attributes of selected elements to the return value of the lambda function
$siteblocks->attr('data-val', function( $i, $val) {
    return $i . " - " . $val->attr('class') . " - photo by Kelly Clark";
});

// Select the parents of selected elements
$parents = $siteblocks->parent();

[...]
```

## Author

| [![twitter/johnslegers](https://en.gravatar.com/avatar/bf4cc94221382810233575862875e687?s=70)](http://twitter.com/johnslegers "Follow @johnslegers on Twitter") |
|---|
| [John slegers](http://www.johnslegers.com/) |
