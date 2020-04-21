<?php

namespace Serwisant\SerwisantApi\Types\SchemaService;

use Serwisant\SerwisantApi;
use Serwisant\SerwisantApi\Types;

class ImageSize extends Types\Enum
{
  /**
  */
  const ORIGINAL = 'ORIGINAL';

  /**
  */
  const THUMBNAIL = 'THUMBNAIL';

  protected function schemaNamespace()
  {
    return 'SchemaService';
  }
}