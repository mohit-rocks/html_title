<?php

/**
 * @file
 * Contains \Drupal\html_title\Plugin\Field\FieldFormatter\HtmlTitleFormatter.
 */

namespace Drupal\html_title\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'html_title_display' formatter.
 *
 * @FieldFormatter(
 *   id = "html_title_display",
 *   label = @Translation("HTML Title"),
 *   field_types = {
 *     "string",
 *   },
 *   quickedit = {
 *     "editor" = "plain_text"
 *   }
 * )
 */
class HtmlTitleFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['link_to_entity'] = TRUE;
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['link_to_entity'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Link to the content'),
      '#default_value' => $this->getSetting('link_to_entity'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    $config = \Drupal::config('html_title.settings');
    $html_title_valid_elements = $config->get('html_title_allowed_elements');
    array_unique($html_title_valid_elements);

    foreach ($items as $delta => $item) {
      /** @var $node \Drupal\node\NodeInterface */
      if ($node = $item->getEntity()) {
        if ($this->getSetting('link_to_entity')) {
          $elements[$delta] = [
            '#type' => 'link',
            '#title' => SafeMarkup::format($node->getTitle(), $html_title_valid_elements),
            '#url' => Url::fromUri('entity:node/' . $node->id(), array()),
            '#link_options' => ['attributes' => ['rel' => 'node']],
          ];
        }
        else {
          $elements[$delta] = [
            '#markup' => SafeMarkup::format($node->getTitle(), $html_title_valid_elements),
          ];
        }
      }
    }

    return $elements;
  }

}
