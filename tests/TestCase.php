<?php namespace Tests;

use Illuminate\Contracts\Console\Kernel;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{

  /**
   * @var string
   */
  protected $baseUrl = 'http://tucano.app';

  /**
   * Boostrap application
   *
   * @return \Illuminate\Foundation\Application
   */
  public function createApplication()
  {
    $app = require __DIR__.'/../bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();

    return $app;
  }

  /**
   * Setup test case
   *
   * @return void
   */
  public function setUp()
  {
    parent::setUp();
  }

  /**
   * Teardown test case
   *
   * @return void
   */
  public function tearDown()
  {
    \Mockery::close();
  }
}

