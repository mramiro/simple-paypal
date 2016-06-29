<?php namespace SimplePaypal\Http;

abstract class ExternalHandler extends BaseHandler
{
  protected function request(array $options)
  {
    $cmd = $this->getCommand();
    $args = $this->buildArgs($options);
    exec($cmd . ' ' . $args, $output, $return);
    if ($return != 0) {
      throw new HttpException();
    }
    return implode("\n", (array)$output);
  }

  protected abstract function getCommand();

  protected abstract function buildArgs(array $config);

}
