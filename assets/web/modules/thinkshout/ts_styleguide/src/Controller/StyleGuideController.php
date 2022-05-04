<?php

namespace Drupal\ts_styleguide\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\Xss;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Error\LoaderError;

/**
 * Creates style guide at /kmc-style-guide.
 */
class StyleGuideController extends ControllerBase {

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * The twig service.
   *
   * @var \Drupal\Core\Template\TwigEnvironment
   */
  protected $twig;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->configFactory = $container->get('config.factory');
    $instance->themeManager = $container->get('theme.manager');
    $instance->twig = $container->get('twig');
    return $instance;
  }

  /**
   * Display TS styleguide for the current theme.
   *
   * @return array
   *   The styleguide markup.
   */
  public function tsStyleGuide() {
    $themename = $this->configFactory->get('system.theme')->get('default');
    try {
      $template = $this->twig->load("@$themename/_includes/styleguide.html.twig");
    }
    catch (LoaderError $e) {
      return [
        '#markup' => $this->t('The current theme "@theme" does not have a template at templates/_includes/styleguide.html', ['@theme' => $themename]),
      ];
    }
    $context = [];
    $allowed_tags = array_merge(Xss::getAdminTagList(),
          [
            'form',
            'label',
            'input',
            'select',
            'option',
            'radio',
            'fieldset',
            'legend',
          ]);
    return [
      '#markup' => $template->render($context),
      '#allowed_tags' => $allowed_tags,
    ];
  }

  /**
   * Access callback for TS styleguide.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function tsStyleGuideAccess() {
    $is_pantheon_live = isset($_ENV['PANTHEON_ENVIRONMENT']) && $_ENV['PANTHEON_ENVIRONMENT'] == 'live';
    return AccessResult::allowedIf(!$is_pantheon_live);
  }

}