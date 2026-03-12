<?php

declare(strict_types=1);

namespace TwigCsFixerDrupal\Tests\Rules\Variable;

use TwigCsFixer\Test\AbstractRuleTestCase;
use TwigCsFixerDrupal\Rules\Variable\DrupalVariableNameRule;

/**
 * @covers \TwigCsFixerDrupal\Rules\Variable\DrupalVariableNameRule
 */
final class DrupalVariableNameRuleTest extends AbstractRuleTestCase {

  public function testValidVariableNames(): void {
    $this->checkRule(
      new DrupalVariableNameRule(),
      [],
      __DIR__ . '/Fixtures/valid.twig',
    );
  }

  public function testInvalidSetVariables(): void {
    $this->checkRule(
      new DrupalVariableNameRule(),
      [
        'DrupalVariableName.Error:1:8' => 'The var name must use snake_case (with optional double underscores); expected camel_case.',
        'DrupalVariableName.Error:2:8' => 'The var name must use snake_case (with optional double underscores); expected pascal_case.',
      ],
      __DIR__ . '/Fixtures/invalid_set.twig',
    );
  }

  public function testInvalidForLoopVariables(): void {
    $this->checkRule(
      new DrupalVariableNameRule(),
      [
        'DrupalVariableName.Error:1:8' => 'The var name must use snake_case (with optional double underscores); expected item.',
        'DrupalVariableName.Error:2:13' => 'The var name must use snake_case (with optional double underscores); expected value.',
      ],
      __DIR__ . '/Fixtures/invalid_for.twig',
    );
  }

  public function testValidOptionalPrefix(): void {
    $this->checkRule(
      new DrupalVariableNameRule('_'),
      [],
      __DIR__ . '/Fixtures/valid_prefix.twig',
    );
  }

  public function testInvalidOptionalPrefix(): void {
    $this->checkRule(
      new DrupalVariableNameRule('_'),
      [
        'DrupalVariableName.Error:1:8' => 'The var name must use snake_case (with optional double underscores); expected _invalid_prefix.',
      ],
      __DIR__ . '/Fixtures/invalid_prefix.twig',
    );
  }

  public function testGetConfiguration(): void {
    $rule = new DrupalVariableNameRule('_');
    self::assertSame(['optionalPrefix' => '_'], $rule->getConfiguration());
  }

}
