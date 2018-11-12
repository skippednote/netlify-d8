<?php

namespace Drupal\netlify\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

/**
 * Class NetlifyForm.
 */
class NetlifyForm extends ConfigFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'netlify_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'netlify.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('netlify.settings');
    $node_types = NodeType::loadMultiple();

    $form['netlify_build_hook_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Netlify Build Hook URL'),
      '#default_value' => $config->get('netlify_build_hook_url'),
    ];

    foreach($node_types as $node_type) {
      $id = $node_type->id();
      $label = $node_type->label();

      $form['netlify_node_type_'.$id] = [
        '#type' => 'checkbox',
        '#options' => $options,
        '#title' => $this->t($label),
        '#default_value' => $config->get('netlify_node_type_'.$id),
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('netlify.settings');
    $node_types = NodeType::loadMultiple();
    $config->set('netlify_build_hook_url', $form_state->getValue('netlify_build_hook_url'));

    foreach($node_types as $node_type) {
      $id = $node_type->id();
      $config->set('netlify_node_type_'.$id, $form_state->getValue('netlify_node_type_'.$id));
    }
    $config->save();
  }

}
