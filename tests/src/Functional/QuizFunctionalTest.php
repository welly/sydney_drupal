<?php

namespace Drupal\sydney_drupal\Tests;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * @group sydney_drupal
 * @group functional
 */

class QuizFunctionalTest extends BrowserTestBase {

  public static $modules = ['sydney_drupal'];

  public function setUp() {
    parent::setUp();

    // Some more initial setup stuff here...
  }

  public function testFormExists() {

    $assert = $this->assertSession();

    $this->drupalGet('/quiz');
    $assert->statusCodeEquals(200);
  }

  public function testSubmitBlankForm() {

    $this->drupalGet('/quiz');

    $assert = $this->assertSession();
    $page = $this->getSession()->getPage();
    $button = $page->findButton('Submit');

    $assert->pageTextContains('Beer Quiz');

    $button->click();
    $assert->addressEquals('/quiz/answers');
    $assert->pageTextContains('You got 0 correct');
  }

  public function testSubmitSemiFilledForm() {

    $this->drupalGet('/quiz');

    $assert = $this->assertSession();
    $page = $this->getSession()->getPage();
    $button = $page->findButton('Submit');

    $page->fillField('question_1', 'Belgium');
    $page->selectFieldOption('question_2', 'Carlton Draught');

    $button->click();
    $assert->addressEquals('/quiz/answers');
    $assert->pageTextContains('You got 2 correct');
  }

  public function testSubmitCorrectAnswers() {

    $this->drupalGet('/quiz');

    $assert = $this->assertSession();
    $page = $this->getSession()->getPage();
    $button = $page->findButton('Submit');

    $page->fillField('question_1', 'Belgium');
    $page->selectFieldOption('question_2', 'Carlton Draught');
    $page->checkField('question_3[Barley]');
    $page->checkField('question_3[Water]');
    $page->checkField('question_3[Hops]');

    $button->click();
    $assert->addressEquals('/quiz/answers');
    $assert->pageTextContains('You got 5 correct!');
    $assert->pageTextContains('You got the maximum score!');
  }

  public function testSubmitIncorrectlyFilledForm() {

    $this->drupalGet('/quiz');

    $assert = $this->assertSession();
    $page = $this->getSession()->getPage();
    $button = $page->findButton('Submit');

    $page->fillField('question_1', 'Penrith');
    $page->selectFieldOption('question_2', 'Crown Lager');

    $button->click();
    $assert->addressEquals('/quiz/answers');
    $assert->pageTextContains('You got 0 correct');
  }

}
