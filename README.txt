
Installation
------------

1. Install and enable like a normal Drupal module.
2. In your settings.php you need to set the $conf variables to the correct 
settings.

If you want to set beanstalkd as the default queue manager then add the 
following to your settings.php

$conf['queue_default_class'] = 'StompQueue';

Alternatively you can also set for each queue to use Stomp

$conf['queue_class_{queue name}'] = 'StompQueue';


Configuration
-------------

Each STOMP queue can set its own headers. These can be defined in additional
$conf variables as below:

For example: by default, each queue will be non-persistant. To set 

$conf['stomp_queue_{queue name}'] = array(
  'headers' => array(
    'persistent' => 'true',
  ),
);