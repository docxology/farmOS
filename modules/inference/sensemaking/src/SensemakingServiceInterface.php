<?php

namespace Drupal\farm_sensemaking;

use Drupal\log\Entity\LogInterface;

/**
 * Interface for the Sensemaking service.
 */
interface SensemakingServiceInterface {

  /**
   * Creates a new sensemaking log.
   *
   * @param string $inference
   *   The perceptual inference or insight.
   * @param float $confidence
   *   The confidence level of the inference.
   * @param array $data_sources
   *   An array of data source asset IDs.
   *
   * @return \Drupal\log\Entity\LogInterface
   *   The created sensemaking log.
   */
  public function createSensemakingLog($inference, $confidence, array $data_sources);

  /**
   * Retrieves sensemaking logs based on criteria.
   *
   * @param array $criteria
   *   An array of criteria to filter the logs.
   *
   * @return array
   *   An array of matching sensemaking logs.
   */
  public function getSensemakingLogs(array $criteria = []);

  /**
   * Gets the current farm state.
   *
   * @return array
   *   An array representing the current farm state.
   */
  public function getCurrentFarmState();

}
