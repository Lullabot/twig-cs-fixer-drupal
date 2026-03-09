<?php

declare(strict_types=1);

namespace TwigCsFixerDrupal;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Template\TwigTransTokenParser;
use Drupal\Core\Theme\ThemeManagerInterface;
use DrupalFinder\DrupalFinderComposerRuntime;
use TwigCsFixer\Config\Config;
use TwigCsFixer\Rules\Whitespace\IndentRule;
use TwigCsFixer\Standard\TwigCsFixer;
use TwigCsFixerDrupal\Rules\Component\RequireComponentAttributesRule;

/**
 * Drupal configuration.
 */
class DrupalConfig {

  public static function getConfig(): Config {
    $config = new Config();

    // Enable reporting of non-fixable rules.
    $config->allowNonFixableRules();

    // Add drupal/core translation token parsers.
    $config->addTokenParser(new TwigTransTokenParser());

    // Add drupal/twig_tweak support.
    if (class_exists('\Drupal\twig_tweak\TwigTweakExtension')) {
      $moduleHandler = new class implements ModuleHandlerInterface {
        public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL) {}
      };
      $themeManager = new class implements ThemeManagerInterface {
        public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL) {}
      };
      $config->addTwigExtension(new \Drupal\twig_tweak\TwigTweakExtension($moduleHandler, $themeManager));
    }

    // Add drupal/storybook support.
    if (class_exists('\TwigStorybook\Twig\TwigExtension') && class_exists('\TwigStorybook\Service\StoryCollector')) {
      $finder = new DrupalFinderComposerRuntime();
      $storyCollector = new \TwigStorybook\Service\StoryCollector();
      $config->addTwigExtension(new \TwigStorybook\Twig\TwigExtension($storyCollector, $finder->getDrupalRoot()));
    }

    // Load default ruleset.
    $ruleset = $config->getRuleset();
    $ruleset->addStandard(new TwigCsFixer());

    // Configure indent rule to match .editorconfig settings from drupal/core.
    // https://git.drupalcode.org/project/drupal/-/blob/11.x/.editorconfig?ref_type=heads
    $ruleset->overrideRule(new IndentRule(
      spaceRatio: 2,
      useTab: FALSE,
    ));

    // Add Drupal specific rules.
    $ruleset->addRule(new RequireComponentAttributesRule());

    $config->setRuleset($ruleset);
    return $config;
  }

}
