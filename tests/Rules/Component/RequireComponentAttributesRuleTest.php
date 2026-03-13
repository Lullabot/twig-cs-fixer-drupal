<?php

declare(strict_types=1);

namespace TwigCsFixerDrupal\Tests\Rules\Component;

use TwigCsFixer\Test\AbstractRuleTestCase;
use TwigCsFixerDrupal\Rules\Component\RequireComponentAttributesRule;

/**
 * @covers \TwigCsFixerDrupal\Rules\Component\RequireComponentAttributesRule
 */
final class RequireComponentAttributesRuleTest extends AbstractRuleTestCase {

  public function testValidComponentWithAttributes(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [],
      __DIR__ . '/Fixtures/components/valid-component/valid-component.twig',
    );
  }

  public function testInvalidComponentWithoutAttributes(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [
        'RequireComponentAttributes.Error:1:1' => "Component's main html tag must have attributes set using attributes prop.",
      ],
      __DIR__ . '/Fixtures/components/invalid-component/invalid-component.twig',
    );
  }

  public function testNonComponentTemplateIsSkipped(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [],
      __DIR__ . '/Fixtures/non_component.twig',
    );
  }

  public function testValidComponentWithCommentBeforeTag(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [],
      __DIR__ . '/Fixtures/components/valid-comment-component/valid-comment-component.twig',
    );
  }

  public function testInvalidComponentWithCommentBeforeTag(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [
        'RequireComponentAttributes.Error:1:14' => "Component's main html tag must have attributes set using attributes prop.",
      ],
      __DIR__ . '/Fixtures/components/invalid-comment-component/invalid-comment-component.twig',
    );
  }

  public function testComponentWithNoHtmlTag(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [],
      __DIR__ . '/Fixtures/components/no-html-component/no-html-component.twig',
    );
  }

  public function testValidComponentWithMacroBeforeTag(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [],
      __DIR__ . '/Fixtures/components/valid-macro-component/valid-macro-component.twig',
    );
  }

  public function testInvalidComponentWithAttributesInContent(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [
        'RequireComponentAttributes.Error:1:1' => "Component's main html tag must have attributes set using attributes prop.",
      ],
      __DIR__ . '/Fixtures/components/invalid-attributes-in-content/invalid-attributes-in-content.twig',
    );
  }

  public function testInvalidComponentWithBlockBeforeTag(): void {
    $this->checkRule(
      new RequireComponentAttributesRule(),
      [
        'RequireComponentAttributes.Error:1:16' => "Component's main html tag must have attributes set using attributes prop.",
      ],
      __DIR__ . '/Fixtures/components/invalid-after-block/invalid-after-block.twig',
    );
  }

}
