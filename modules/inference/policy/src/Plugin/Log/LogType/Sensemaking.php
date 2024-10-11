<?php

namespace Drupal\farm_sensemaking\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the sensemaking log type.
 *
 * @LogType(
 *   id = "sensemaking",
 *   label = @Translation("Sensemaking"),
 * )
 */
class Sensemaking extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Add custom fields for sensemaking logs.
    $fields['inference'] = \Drupal\Core\Field\BaseFieldDefinition::create('text_long')
      ->setLabel(t('Inference'))
      ->setDescription(t('The perceptual inference or insight gained.'))
      ->setRequired(TRUE);

    $fields['confidence'] = \Drupal\Core\Field\BaseFieldDefinition::create('list_float')
      ->setLabel(t('Confidence'))
      ->setDescription(t('The confidence level of the inference (0-1).'))
      ->setSettings([
        'allowed_values' => [
          '0.25' => t('Low'),
          '0.5' => t('Medium'),
          '0.75' => t('High'),
          '1' => t('Very High'),
        ],
      ])
      ->setRequired(TRUE);

    $fields['data_sources'] = \Drupal\Core\Field\BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Data Sources'))
      ->setDescription(t('Reference to the data sources used for this inference.'))
      ->setCardinality(\Drupal\Core\Field\FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setSetting('target_type', 'asset');

    return $fields;
  }

}
