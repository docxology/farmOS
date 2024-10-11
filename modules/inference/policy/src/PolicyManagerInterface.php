<?php

namespace Drupal\farm_policy;

/**
 * Interface for the Policy Manager service.
 */
interface PolicyManagerInterface {

  /**
   * Evaluates and selects policies using Active Inference methods.
   */
  public function evaluatePolicies();

}