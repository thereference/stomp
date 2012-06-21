<?php

/**
 * @file
 */

class StompQueue implements DrupalReliableQueueInterface {
  
  
  protected $name;
  
  
  /**
   * Start working with a queue.
   *
   * @param $name
   *   Arbitrary string. The name of the queue to work with.
   */
  public function __construct($name) {
    require_once('lib/Stomp.php');
    
    $this->name = $name;
    
    $this->stomp_params = stomp_get_queue_options($name);
    $this->createconnection();
  }
  
  public function __destruct() {
    $this->disconnect();
  }
  
  
  public function createconnection(){
    $connection = $this->stomp_params['protocol'] . '://';
    $connection .= $this->stomp_params['host'] . ':' . $this->stomp_params['port'];
    
    $this->stomp = new Stomp($connection);
    
    // connect
    $this->stomp->connect();
  }
  
  /**
   * Create a queue.
   *
   * Called during installation and should be used to perform any necessary
   * initialization operations. This should not be confused with the
   * constructor for these objects, which is called every time an object is
   * instantiated to operate on a queue. This operation is only needed the
   * first time a given queue is going to be initialized (for example, to make
   * a new database table or directory to hold tasks for the queue -- it
   * depends on the queue implementation if this is necessary at all).
   */
  public function createQueue() {
    // STOMP queues are created on demand, so the first time an item is created
    // for a queue which does not exist then it will be created within the 
    // destination broker.
  }
  
  /**
   * Delete a queue.
   *
   * @param $item
   *   The item returned by DrupalQueueInterface::claimItem().
   */
  public function deleteQueue() {
    // STOMP does not provide a mechanism to delete queues from the source
    // broker.
  }
  
  /**
   * Add a queue item and store it directly to the queue.
   *
   * @param $data
   *   Arbitrary data to be associated with the new task in the queue.
   * @return
   *   TRUE if the item was successfully created and was (best effort) added
   *   to the queue, otherwise FALSE. We don't guarantee the item was
   *   committed to disk, that your disk wasn't hit by a meteor, etc, but as
   *   far as we know, the item is now in the queue.
   */
  public function createItem($data) {
    try {
      // send a message to the queue
      $this->stomp->send("/queue/" . $this->name, serialize($data), $this->stomp_params['headers']);
      // disconnect
      $this->disconnect();
    }
    catch (StompException $e) {
      var_dump($e);
      echo "Error creating queue item";
      
      return FALSE;
    }
    
    return TRUE;
  }
  
  /**
   * Retrieve the number of items in the queue.
   *
   * This is intended to provide a "best guess" count of the number of items in
   * the queue. Depending on the implementation and the setup, the accuracy of
   * the results of this function may vary.
   *
   * e.g. On a busy system with a large number of consumers and items, the
   * result might only be valid for a fraction of a second and not provide an
   * accurate representation.
   *
   * @return
   *   An integer estimate of the number of items in the queue.
   */
  public function numberOfItems() {
    // STOMP does not provide a facility to count the number of items on a queue
    // so this function is not required.
  }
  
  /**
   * Claim an item in the queue for processing.
   *
   * @param $lease_time
   *   How long the processing is expected to take in seconds, defaults to an
   *   hour. After this lease expires, the item will be reset and another
   *   consumer can claim the item. For idempotent tasks (which can be run
   *   multiple times without side effects), shorter lease times would result
   *   in lower latency in case a consumer fails. For tasks that should not be
   *   run more than once (non-idempotent), a larger lease time will make it
   *   more rare for a given task to run multiple times in cases of failure,
   *   at the cost of higher latency.
   * @return
   *   On success we return an item object. If the queue is unable to claim an
   *   item it returns false. This implies a best effort to retrieve an item
   *   and either the queue is empty or there is some other non-recoverable
   *   problem.
   */
  public function claimItem($lease_time = 3600, $timeout = TRUE) {
    // subscribe to the queue
    $this->stomp->subscribe("/queue/" . $this->name);
    
    if ($this->stomp->hasFrameToRead()) {
      // receive a message from the queue
      $item = $this->stomp->readFrame();

      return $item;
    }
    else {
      return FALSE;
    }
  }
  
  /**
   * Delete a finished item from the queue.
   *
   * @param $item
   *   The item returned by DrupalQueueInterface::claimItem().
   */
  public function deleteItem($item) {
    $this->stomp->ack($item->headers['message-id']);
  }
  
  /**
   * Release an item that the worker could not process, so another
   * worker can come in and process it before the timeout expires.
   *
   * @param $item
   * @return boolean
   */
  public function releaseItem($item) {
    
  }
  
  public function disconnect(){
    $this->stomp->disconnect();
  }
  

}