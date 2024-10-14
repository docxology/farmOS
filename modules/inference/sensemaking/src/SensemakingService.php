<?php

namespace Drupal\farm_sensemaking;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\asset\Entity\AssetInterface;
use Drupal\farm_weather\WeatherDataInterface;
use Drupal\farm_soil\SoilDataInterface;

/**
 * Service for handling sensemaking operations in farmOS.
 *
 * This service provides methods to create and retrieve sensemaking logs,
 * as well as to analyze the current farm state based on various data sources.
 */
class SensemakingService implements SensemakingServiceInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * The weather data service.
   *
   * @var \Drupal\farm_weather\WeatherDataInterface
   */
  protected $weatherData;

  /**
   * The soil data service.
   *
   * @var \Drupal\farm_soil\SoilDataInterface
   */
  protected $soilData;

  /**
   * Constructs a new SensemakingService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger channel factory.
   * @param \Drupal\farm_weather\WeatherDataInterface $weather_data
   *   The weather data service.
   * @param \Drupal\farm_soil\SoilDataInterface $soil_data
   *   The soil data service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    LoggerChannelFactoryInterface $logger_factory,
    WeatherDataInterface $weather_data,
    SoilDataInterface $soil_data
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->logger = $logger_factory->get('farm_sensemaking');
    $this->weatherData = $weather_data;
    $this->soilData = $soil_data;
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
    try {
      $log_storage = $this->entityTypeManager->getStorage('log');
      $log = $log_storage->create([
        'type' => 'sensemaking',
        'inference' => $inference,
        'confidence' => $confidence,
        'data_sources' => $data_sources,
      ]);
      $log->save();
      $this->logger->info('Created new sensemaking log with ID: @id', ['@id' => $log->id()]);
      return $log;
    }
    catch (\Exception $e) {
      $this->logger->error('Failed to create sensemaking log: @error', ['@error' => $e->getMessage()]);
      throw $e;
    }
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

  /**
   * Retrieves the current farm state.
   *
   * @return array
   *   An array of current farm state data.
   */
  public function getCurrentFarmState() {
    $this->logger->info('Gathering current farm state');
    $farm_state = [];

    // Get all active crops.
    $crop_assets = $this->getActiveAssets('plant');
    $farm_state['crops'] = $this->summarizeAssets($crop_assets);

    // Get all active animals.
    $animal_assets = $this->getActiveAssets('animal');
    $farm_state['animals'] = $this->summarizeAssets($animal_assets);

    // Get recent weather data.
    $farm_state['weather'] = $this->getRecentWeatherData();

    // Get soil conditions.
    $farm_state['soil'] = $this->getSoilConditions();

    // Add more farm state data
    $farm_state['equipment'] = $this->getActiveEquipment();
    $farm_state['water_resources'] = $this->getWaterResources();
    $farm_state['labor'] = $this->getLaborStatus();

    $this->logger->info('Completed gathering farm state');
    return $farm_state;
  }

  /**
   * Get active assets of a specific type.
   *
   * @param string $asset_type
   *   The type of asset to retrieve.
   *
   * @return \Drupal\asset\Entity\AssetInterface[]
   *   An array of active assets of the specified type.
   */
  protected function getActiveAssets($asset_type) {
    $asset_storage = $this->entityTypeManager->getStorage('asset');
    $query = $asset_storage->getQuery()
      ->condition('type', $asset_type)
      ->condition('status', 'active');
    $asset_ids = $query->execute();
    return $asset_storage->loadMultiple($asset_ids);
  }

  /**
   * Summarize assets for the farm state.
   *
   * @param \Drupal\asset\Entity\AssetInterface[] $assets
   *   An array of assets to summarize.
   *
   * @return array
   *   A summary of the assets.
   */
  protected function summarizeAssets(array $assets) {
    $summary = [];
    foreach ($assets as $asset) {
      $summary[] = [
        'id' => $asset->id(),
        'name' => $asset->label(),
        'type' => $asset->bundle(),
        // Add more relevant asset data here.
      ];
    }
    return $summary;
  }

  /**
   * Get recent weather data.
   *
   * @return array
   *   An array of recent weather data.
   */
  protected function getRecentWeatherData() {
    $this->logger->info('Fetching recent weather data');
    return $this->weatherData->getRecentWeatherData();
  }

  /**
   * Get current soil conditions.
   *
   * @return array
   *   An array of current soil conditions.
   */
  protected function getSoilConditions() {
    $this->logger->info('Fetching current soil conditions');
    return $this->soilData->getCurrentSoilConditions();
  }

  /**
   * Get active equipment.
   *
   * @return array
   *   An array of active equipment assets.
   */
  protected function getActiveEquipment() {
    $equipment_assets = $this->getActiveAssets('equipment');
    return $this->summarizeAssets($equipment_assets);
  }

  /**
   * Get water resources.
   *
   * @return array
   *   An array of water resource data.
   */
  protected function getWaterResources() {
    // Implement logic to fetch water resource data
    // This could involve querying water-related assets or logs
    return [];
  }

  /**
   * Get labor status.
   *
   * @return array
   *   An array of labor status data.
   */
  protected function getLaborStatus() {
    // Implement logic to fetch labor status
    // This could involve querying user entities or labor-related logs
    return [];
  }

}
