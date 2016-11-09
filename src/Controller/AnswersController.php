<?php

namespace Drupal\sydney_drupal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\State\State;

/**
 * Class AnswersController.
 *
 * @package Drupal\sydney_drupal\Controller
 */
class AnswersController extends ControllerBase  {

  const QUESTION_ONE_ANSWER = 'jamaica';
  const QUESTION_TWO_ANSWER = 'Carlton Draught';
  const QUESTION_THREE_ANSWER = ['Barley', 'Water', 'Hops'];
  const MAX_SCORE = 5;

  /**
   * Results.
   *
   * @return string
   *   Return Hello string.
   */
  public function answers() {

    $correct_answers = 0;

    foreach (range(1, 3) as $number) {
      $answers['question_' . $number] = \Drupal::state()->get('sydney_drupal.question_' . $number);
    }

    foreach($answers as $question => $answer) {
      switch($question) {
        case 'question_1':
          if ($this->parseQuestionOne($answer)) {
            $correct_answers += 1;
          }
          break;
        case 'question_2':
          if ($this->parseQuestionTwo($answer)) {
            $correct_answers += 1;
          }
          break;
        case 'question_3':
          $correct_answers += $this->parseQuestionThree($answer);
      }
    }

    $return['correct'] = [
      '#prefix' => '<p>',
      '#markup' => $this->t('You got %correct correct!', ['%correct' => $correct_answers]),
      '#suffix' => '</p>',
    ];

    if ($correct_answers == $this::MAX_SCORE) {
      $return['max_score'] = [
        '#prefix' => '<p>',
        '#markup' => $this->t('You got the maximum score!'),
        '#suffix' => '</p>',
      ];
    }

    return $return;
  }

  public function parseQuestionOne($answer) {

    $answer = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower(trim($answer)));

    if ($answer == $this::QUESTION_ONE_ANSWER) {
      return true;
    }

    return false;
  }

  public function parseQuestionTwo($answer) {

    if ($answer == $this::QUESTION_TWO_ANSWER) {
      return true;
    }

    return false;
  }

  public function parseQuestionThree($answer) {
    $correct_ingredients = 0;

    $answer = array_filter(array_values($answer));

    foreach($answer as $ingredient) {
      foreach ($this::QUESTION_THREE_ANSWER as $valid_ingredient) {
        if ($ingredient == $valid_ingredient) {
          $correct_ingredients += 1;
        }
      }
    }

    return $correct_ingredients;
  }

  private static function flatten($array) {
    if (!is_array($array)) {
      return array($array);
    }
  }

}
