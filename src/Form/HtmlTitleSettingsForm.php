<?php
/**
 * @file
 * Contains \Drupal\html_title\Form\HtmlTitleSettingsForm.
 */

namespace Drupal\html_title\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;

/**
 * Configure Configure HTML tags used in node titles.
 */
class HtmlTitleSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'html_title_admin_settings';
  }
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['html_title.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('html_title.settings');

    $form['description'] = array(
      '#value' => $this->t('Only the HTML tags below may be allowed in node titles. Any tags not enabled here will be removed. Note that all HTML will be removed in feed output and JSON output in views.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    );

    $form['html_title_allowed_elements'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Available tags'),
      '#default_value' => $config->get('html_title_allowed_elements'),
      '#options' => array(
        'abbr' => Html::escape('<abbr>'),
        'b' => Html::escape('<b>'),
        'bdi' => Html::escape('<bdi>'),
        'cite' => Html::escape('<cite>'),
        'code' => Html::escape('<code>'),
        'em' => Html::escape('<em>'),
        'i' => Html::escape('<i>'),
        'strong' => Html::escape('<strong>'),
        'sub' => Html::escape('<sub>'),
        'sup' => Html::escape('<sup>'),
        'wbr' => Html::escape('<wbr>'),
      ),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('html_title.settings')
      ->set('html_title_allowed_elements', $form_state->getValue('html_title_allowed_elements'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
