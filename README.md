# Twig CS Fixer Drupal

[Drupal](https://www.drupal.org/) specific config for [Twig CS Fixer](https://github.com/VincentLanglet/Twig-CS-Fixer).

## Installation

- [Install Twig CS Fixer](https://github.com/VincentLanglet/Twig-CS-Fixer?tab=readme-ov-file#installation)
- `composer req --dev lullabot/twig-cs-fixer-drupal`
- In your Twig CS Fixer [configuration file](https://github.com/VincentLanglet/Twig-CS-Fixer/blob/main/docs/configuration.md) enable predefined Drupal specific config:

  ```php
  <?php
  // Load Drupal TwigCsFixer configuration.
  return \TwigCsFixerDrupal\DrupalConfig::getConfig();
  ```

## Custom rules

| Rule | Fixable | Description |
| -----| --------| ------------|
| [RequireComponentAttributesRule](src/Rules/Component/RequireComponentAttributesRule.php) | no | Ensures that main html tag of a component has attributes set using `attributes` prop. |
