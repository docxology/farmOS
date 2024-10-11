<?php

namespace Drupal\farm_sensemaking;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Service for handling sensemaking operations.
 */
class SensemakingService {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new SensemakingService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

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
  public function createSensemakingLog($inference, $confidence, array $data_sources) {
    $log_storage = $this->entityTypeManager->getStorage('log');
    $log = $log_storage->create([
      'type' => 'sensemaking',
      'inference' => $inference,
      'confidence' => $confidence,
      'data_sources' => $data_sources,
    ]);
    $log->save();
    return $log;
  }

  /**
   * Retrieves sensemaking logs based on criteria.
   *
   * @param array $criteria
   *   An array of criteria to filter the logs.
   *
   * @return array
   *   An array of matching sensemaking logs.
   */
  public function getSensemakingLogs(array $criteria = []) {
    $query = $this->entityTypeManager->getStorage('log')->getQuery()
      ->condition('type', 'sensemaking');

    foreach ($criteria as $field => $value) {
      $query->condition($field, $value);
    }

    $log_ids = $query->execute();
    return $this->entityTypeManager->getStorage('log')->loadMultiple($log_ids);
  }

}