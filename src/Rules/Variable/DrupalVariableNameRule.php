<?php

declare(strict_types=1);

namespace TwigCsFixerDrupal\Rules\Variable;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Rules\ConfigurableRuleInterface;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;
use TwigCsFixer\Util\StringUtil;
use Webmozart\Assert\Assert;

/**
 * Ensures that variable names use snake_case, allowing double underscores.
 *
 * Drupal commonly uses double underscores (__) as a naming convention
 * (e.g. BEM-style: lets_talk__base_class). This rule validates each
 * double-underscore-separated segment independently as snake_case.
 */
final class DrupalVariableNameRule extends AbstractRule implements ConfigurableRuleInterface
{

  public function __construct(
    private readonly string $optionalPrefix = '',
  ) {
  }

  public function getConfiguration(): array {
    return [
      'optionalPrefix' => $this->optionalPrefix,
    ];
  }

  protected function process(int $tokenIndex, Tokens $tokens): void {
    $token = $tokens->get($tokenIndex);

    if ($token->isMatching(Token::BLOCK_NAME_TYPE, 'set')) {
      $nameTokenIndex = $tokens->findNext(Token::NAME_TYPE, $tokenIndex);
      Assert::notFalse($nameTokenIndex, 'A BLOCK_NAME_TYPE "set" must be followed by a name');

      $this->validateVariable($tokens->get($nameTokenIndex));
    }
    elseif ($token->isMatching(Token::BLOCK_NAME_TYPE, 'for')) {
      $nameTokenIndex = $tokens->findNext(Token::NAME_TYPE, $tokenIndex);
      Assert::notFalse($nameTokenIndex, 'A BLOCK_NAME_TYPE "for" must be followed by a name');

      $secondNameTokenIndex = $tokens->findNext([Token::NAME_TYPE, Token::OPERATOR_TYPE], $nameTokenIndex + 1);
      Assert::notFalse($secondNameTokenIndex, 'A BLOCK_NAME_TYPE "for" must use the "in" operator');

      $this->validateVariable($tokens->get($nameTokenIndex));
      if ($tokens->get($secondNameTokenIndex)->isMatching(Token::NAME_TYPE)) {
        $this->validateVariable($tokens->get($secondNameTokenIndex));
      }
    }
  }

  private function validateVariable(Token $token): void {
    $name = $token->getValue();
    $prefix = '';
    if (str_starts_with($name, $this->optionalPrefix)) {
      $prefix = $this->optionalPrefix;
      $name = substr($name, \strlen($this->optionalPrefix));
    }

    $expected = $this->toDrupalSnakeCase($name);

    if ($expected !== $name) {
      $this->addError(
        \sprintf('The var name must use snake_case (with optional double underscores); expected %s.', $prefix.$expected),
        $token,
      );
    }
  }

  /**
   * Converts a string to Drupal snake_case, preserving double underscores.
   *
   * Double underscores are used as a naming convention in Drupal (e.g. BEM).
   * Each segment between double underscores is independently validated as
   * snake_case.
   */
  private function toDrupalSnakeCase(string $string): string {
    $parts = explode('__', $string);
    $parts = array_map([StringUtil::class, 'toSnakeCase'], $parts);
    return implode('__', $parts);
  }

}
