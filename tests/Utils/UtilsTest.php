<?php

declare(strict_types=1);

namespace TwigCsFixerDrupal\Tests\Utils;

use PHPUnit\Framework\TestCase;
use TwigCsFixer\Token\Token;
use TwigCsFixerDrupal\Utils;

/**
 * @covers \TwigCsFixerDrupal\Utils
 */
final class UtilsTest extends TestCase {

  public function testReturnsFalseForEmptyFilename(): void {
    $token = new Token(Token::TEXT_TYPE, 1, 1, '');
    self::assertFalse(Utils::isInComponentTemplate($token));
  }

  public function testReturnsFalseForNonTwigExtension(): void {
    // Use a real PHP file in the project - not a .twig file.
    $token = new Token(Token::TEXT_TYPE, 1, 1, __DIR__ . '/../../src/Utils.php');
    self::assertFalse(Utils::isInComponentTemplate($token));
  }

  public function testReturnsFalseWhenNotInComponentsDirectory(): void {
    $token = new Token(Token::TEXT_TYPE, 1, 1, __DIR__ . '/Fixtures/template.twig');
    self::assertFalse(Utils::isInComponentTemplate($token));
  }

  public function testReturnsFalseWhenComponentYmlMissing(): void {
    // In a components/ directory, but no matching .component.yml file.
    $token = new Token(Token::TEXT_TYPE, 1, 1, __DIR__ . '/Fixtures/components/no-yml/no-yml.twig');
    self::assertFalse(Utils::isInComponentTemplate($token));
  }

  public function testReturnsTrueForValidComponentTemplate(): void {
    // Uses the component fixture that has a matching .component.yml.
    $path = __DIR__ . '/../Rules/Component/Fixtures/components/valid-component/valid-component.twig';
    $token = new Token(Token::TEXT_TYPE, 1, 1, $path);
    self::assertTrue(Utils::isInComponentTemplate($token));
  }

}
