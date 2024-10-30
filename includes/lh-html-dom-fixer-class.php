<?php

class LH_Html_dom_fixer_class {

var $options;


private function removeElementsByTagNames($dom, $tagNames){
foreach($tagNames as $tagName ){
  $nodeList = $dom->getElementsByTagName($tagName);
  for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0; ) {
    $node = $nodeList->item($nodeIdx);
    $node->parentNode->removeChild($node);
  }
}
}


private function removeTagkeepcontent($dom, $tagName) {

    $nodes = $dom->getElementsByTagName($tagName);

    while ($node = $nodes->item(0)) {
        $replacement = $dom->createDocumentFragment();
        while ($inner = $node->childNodes->item(0)) {
            $replacement->appendChild($inner);
        }
        $node->parentNode->replaceChild($replacement, $node);
    }


}


private function removeAttributeByAttributeNames($attributeNames, $document) {
foreach($attributeNames as $attributeName ){
foreach($document->getElementsByTagName('*') as $element ){
if ($element->getAttribute($attributeName)){
$element->removeAttribute($attributeName);
}
}
}
}


private function wrapSpecifiedElements($dom) {

//Create new wrapper figure
$wrapper = $dom->createElement('figure');


//Find all iframes
$iframes = $dom->getElementsByTagName('iframe');

//Iterate though iframes
foreach ($iframes AS $iframe) {
    //Clone our created figure
    $wrapper_clone = $wrapper->cloneNode();
    //Replace image with this wrapper div
    $iframe->parentNode->replaceChild($wrapper_clone,$iframe);
    //Append this image to wrapper div
    $wrapper_clone->appendChild($iframe);
}

//Find all images
$images = $dom->getElementsByTagName('img');

//Iterate though images
foreach ($images AS $image) {


$parent = $image->parentNode;

if ($parent->nodeName == 'a'){

    //Clone our created figure
    $wrapper_clone = $wrapper->cloneNode();
    //Replace parent with this wrapper div
    $parent->parentNode->replaceChild($wrapper_clone,$parent);
    //Append this parent to wrapper figure
    $wrapper_clone->appendChild($parent);

} else {


    //Clone our created figure
    $wrapper_clone = $wrapper->cloneNode();
    //Replace image with this wrapper div
    $image->parentNode->replaceChild($wrapper_clone,$image);
    //Append this image to wrapper figure
    $wrapper_clone->appendChild($image);

}
}

}



private function removeElementsfromParagraph($dom, $tags) {

//Iterate though images
foreach ($tags AS $tag) {

//Find all images
$elements = $dom->getElementsByTagName($tag);

//Iterate though occurrences
foreach ($elements AS $element) {

$parent = $element->parentNode;
$grandparent = $parent->parentNode;

if ($parent->nodeName == 'p'){

$clone = $element->cloneNode(true);
$grandparent->insertBefore($clone, $parent); 
$parent->removeChild($element);


}

}


}

}


private function moveSpecifiedElements($dom) {

//Find all images
$figures = $dom->getElementsByTagName('figure');

//Iterate though figures
foreach ($figures AS $figure) {

$parent = $figure->parentNode;
$grandparent = $parent->parentNode;

if ($parent->nodeName == 'p'){

$clone = $figure->cloneNode(true);
$grandparent->insertBefore($clone, $parent); 
$parent->removeChild($figure);


}


}

}



private function removeEmptyElements($doc) {

$elems = array('span','p','h1');

foreach($elems as $elem ){

$domNodeList = $doc->getElementsByTagname($elem);
$domElemsToRemove = array();
foreach ( $domNodeList as $domElement ) {
  $domElement->normalize();
  if (trim($domElement->textContent, "\xc2\xa0 \n \t ") == "") {
    $domElemsToRemove[] = $domElement;
  }
}

foreach( $domElemsToRemove as $domElement ){
    try {
      $domElement->parentNode->removeChild($domElement);
    } catch (Exception $e) {
      //node was already deleted.
      //There's a better way to do this, it's recursive.
    }
}

}
}

private function replaceElements($doc, $find, $replace) {

$elements = $doc->getElementsByTagName($find);
for ($i = $elements->length - 1; $i >= 0; $i --) {
    $nodePre = $elements->item($i);
    $nodeDiv = $doc->createElement($replace, $nodePre->nodeValue);
    $nodePre->parentNode->replaceChild($nodeDiv, $nodePre);
}


}

public function __construct($args) {

$this->options = $args;


}

public function run_from_html_doc_as_string_return_body_content($html_doc_as_string) {


$dom = new DOMDocument;
  

  //suppress warnings as html is unlikely to be well formed
@$dom->loadHTML($html_doc_as_string);


$dom->normalizeDocument();

// Remove blacklisted attributes
$this->removeAttributeByAttributeNames(array('style','target'), $dom);

// Remove blacklisted tags with their content
$this->removeElementsByTagNames($dom, array('style','script'));

$this->removeTagkeepcontent($dom, 'span');

$this->removeTagkeepcontent($dom, 'mark');

$this->removeTagkeepcontent($dom, 'div');

$this->removeTagkeepcontent($dom, 'data');

$this->wrapSpecifiedElements($dom);

$this->removeElementsfromParagraph($dom, array('figure'));

$this->removeEmptyElements($dom);

$this->replaceElements($dom, 'h1', 'h2');

$this->replaceElements($dom, 'h3', 'h2');

$this->replaceElements($dom, 'h4', 'h2');


$body = $dom->documentElement->lastChild;

//very ugly regex, should do this via dom


preg_match("/<body[^>]*>(.*?)<\/body>/is", $dom->saveHTML($body), $matches);

return $matches[1];


}



}


?>