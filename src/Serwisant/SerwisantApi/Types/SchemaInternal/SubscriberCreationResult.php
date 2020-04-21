<?php

namespace Serwisant\SerwisantApi\Types\SchemaInternal;

use Serwisant\SerwisantApi;
use Serwisant\SerwisantApi\Types;

class SubscriberCreationResult extends Types\Type
{
  /**
   * @var Error[]
  */
  public $errors = [];
  /**
   * @var Subscriber
  */
  public $subscriber;

  protected function schemaNamespace()
  {
    return 'SchemaInternal';
  }
}