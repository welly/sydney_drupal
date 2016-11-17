<?php

namespace Drupal\Tests\sydney_drupal\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\sydney_drupal\Controller\AnswersController;

/**
 * Explanation as to the purpose of this test class.
 *
 * @group sydney_drupal
 * @group unit
 */
class QuizUnitTest extends UnitTestCase {

  public $answersController;

  public function setUp() {
    parent::setUp();
    $this->answersController = new AnswersController();
  }

  /**
   * Testing parseQuestionOne function.
   */
  public function testParseQuestionOne() {
    $this->assertEquals(true, $this->answersController->parseQuestionOne('Belgium'));
    $this->assertEquals(true, $this->answersController->parseQuestionOne('BELGIUM'));
    $this->assertEquals(true, $this->answersController->parseQuestionOne('Belgium!!'));
    $this->assertTrue($this->answersController->parseQuestionOne('Belgium...'));
    $this->assertFalse($this->answersController->parseQuestionOne('Parramatta'));
  }

  /**
   * Testing parseQuestionTwo function.
   */
  public function testParseQuestionTwo() {

    $this->assertEquals(true, $this->answersController->parseQuestionTwo('Carlton Draught'));
    $this->assertEquals(false, $this->answersController->parseQuestionTwo('Guinness'));
    $this->assertFalse($this->answersController->parseQuestionTwo('carlton draught'));
    $this->assertFalse($this->answersController->parseQuestionTwo('VB'));
  }

  /**
   * Testing parseQuestionThree function.
   */
  public function testParseQuestionThree() {
    $this->assertEquals(2, $this->answersController->parseQuestionThree(['Barley', 'Water', 'Potatoes']));
    $this->assertEquals(1, $this->answersController->parseQuestionThree(['Barley', 'Carrots', 'Potatoes']));
    $this->assertEquals(0, $this->answersController->parseQuestionThree(['Bananas', 'Carrots', 'Potatoes']));
  }

  /**
   * Testing parseQuestionThree function with data provider.
   *
   * @dataProvider providerQuestionThree
   */
  public function testParseQuestionThreeProvider($expected, $ingredients) {
    $this->assertEquals($expected, $this->answersController->parseQuestionThree($ingredients));
  }

  public function providerQuestionThree() {
    return [
      [3, ['Barley', 'Water', 'Hops']],
      [2, ['Barley', 'Water', 'Potatoes']],
      [1, ['Barley', 'Carrots', 'Potatoes']],
      [0, ['Bananas', 'Carrots', 'Potatoes']],
      [0, ['Chicken', 'Ham', 'Beef']],
      [0, ['Big Mac', 'Whopper', 'Kebab']],
    ];
  }

  /**
   * Testing parseQuestionOne function using mock.
   *
   * @dataProvider providerQuestionOneMock
   */
  public function testParseQuestionOneMock($expected, $location) {

    $answersController = $this->getMockAnswerResponse();

    $this->assertEquals($expected, $answersController->parseQuestionOne($location));
  }

  public function providerQuestionOneMock() {
    return [
      [true, 'Jamaica'],
      [true, 'Brentford'],
      [true, 'Penrith'],
    ];
  }

  public function getMockAnswerResponse() {
    $answer = $this->getMockBuilder(AnswersController::class)
      ->setMethods(['parseQuestionOne'])
      ->getMock();

    $answer->expects($this->any())
      ->method('parseQuestionOne')
      ->willReturn(TRUE);

    return $answer;

  }

}
