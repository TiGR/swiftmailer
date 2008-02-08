<?php

require_once 'Swift/AbstractSwiftUnitTestCase.php';
require_once 'Swift/Mime/Header/DateHeader.php';

class Swift_Mime_Header_DateHeaderTest
  extends Swift_AbstractSwiftUnitTestCase
{
  
  /* --
  The following tests refer to RFC 2822, section 3.6.1 and 3.3.
  */
  
  public function testGetTimestamp()
  {
    $timestamp = time();
    $header = $this->_getHeader('Date');
    $header->setTimestamp($timestamp);
    $this->assertIdentical($timestamp, $header->getTimestamp());
  }
  
  public function testTimestampCanBeSetBySetter()
  {
    $timestamp = time();
    $header = $this->_getHeader('Date');
    $header->setTimestamp($timestamp);
    $this->assertIdentical($timestamp, $header->getTimestamp());
  }
  
  public function testIntegerTimestampIsConvertedToRfc2822Date()
  {
    $timestamp = time();
    $header = $this->_getHeader('Date');
    $header->setTimestamp($timestamp);
    $this->assertEqual(date('r', $timestamp), $header->getFieldBody());
  }
  
  public function testToString()
  {
    $timestamp = time();
    $header = $this->_getHeader('Date');
    $header->setTimestamp($timestamp);
    $this->assertEqual('Date: ' . date('r', $timestamp) . "\r\n",
      $header->toString()
      );
  }
  
  public function testFieldChangeObserverCanSetDate()
  {
    $header = $this->_getHeader('Date');
    $header->fieldChanged('date', 12345);
    $this->assertEqual(12345, $header->getTimestamp());
  }
  
  public function testDateFieldChangeIsIgnoredByOtherHeaders()
  {
    $header = $this->_getHeader('Received');
    $header->setTimestamp(123456);
    $header->fieldChanged('date', 123);
    $this->assertEqual(123456, $header->getTimestamp());
  }
  
  public function testOtherFieldChangesAreIgnoredForDate()
  {
    $header = $this->_getHeader('Date');
    $header->setTimestamp(123);
    foreach (array('charset', 'comments', 'x-foo') as $field)
    {
      $header->fieldChanged($field, 'xxxxx');
      $this->assertEqual(123, $header->getTimestamp());
    }
  }
  
  // -- Private methods
  
  private function _getHeader($name)
  {
    return new Swift_Mime_Header_DateHeader($name);
  }
  
}
