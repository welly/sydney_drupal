<?php

namespace Drupal\sydney_drupal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\State\State;

/**
 * Class QuizForm.
 *
 * @package Drupal\sydney_drupal\Form
 */
class QuizForm extends FormBase {

  /**
   * Drupal\Core\State\State definition.
   *
   * @var \Drupal\Core\State\State
   */
  protected $stateStore;

  /**
   * QuizForm constructor.
   * @param \Drupal\Core\Form\FormBuilder $form_builder
   * @param \Drupal\Core\State\State $state_store
   */
  public function __construct(State $state_store) {
    $this->stateStore = $state_store;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'quiz_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['introduction'] = [
      '#markup' => $this->t('Welcome to the Sydney Drupal Beer Quiz!'),
    ];

    $form['question_1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('1. Which nation does the lager &#039;Red Stripe&#039; come from?'),
      '#maxlength' => 64,
      '#size' => 64,
    ];

    $beers = [
      $this->t('Cooper\'s Pale Ale'),
      $this->t('Victoria Bitter'),
      $this->t('Carlton Draught'),
      $this->t('Crown Lager'),
    ];

    $options = array_combine($beers, $beers);
    $form['question_2'] = [
      '#type' => 'select',
      '#title' => $this->t('2. Which is Australia\'s best selling beer?'),
      '#options' => $options,
    ];

    $ingredients = [
      $this->t('Barley'),
      $this->t('Water'),
      $this->t('Grapes'),
      $this->t('Hops'),
      $this->t('Potatoes'),
    ];
    $options = array_combine($ingredients, $ingredients);

    $form['question_3'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('3. Which ingredients are traditionally used in making beer?'),
      '#options' => $options,
    ];

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    foreach ($form_state->getValues() as $key => $value) {
      $this->stateStore->set('sydney_drupal.' . $key, $value);
    }

    $form_state->setRedirect('sydney_drupal.answers');
  }

}
