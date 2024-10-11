<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* themes/gin/templates/node/node-edit-form.html.twig */
class __TwigTemplate_431cb8279efa5618a8da591f2f81e108 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 18
        yield "<div class=\"layout-node-form clearfix\">
  <div class=\"layout-region layout-region-node-main\">
    ";
        // line 20
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["form"] ?? null), 20, $this->source), "advanced", "footer", "actions", "gin_actions", "gin_sidebar", "gin_sidebar_toggle"), "html", null, true);
        yield "
  </div>
  <div class=\"layout-region layout-region-node-secondary\" id=\"gin_sidebar\">
    <div class=\"layout-region__content\">
      ";
        // line 24
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "advanced", [], "any", false, false, true, 24), 24, $this->source), "html", null, true);
        yield "
      ";
        // line 25
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "gin_sidebar_toggle", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
        yield "
    </div>
  </div>
  <div class=\"layout-node-form__actions\">
    ";
        // line 29
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "gin_actions", [], "any", false, false, true, 29), 29, $this->source), "html", null, true);
        yield "
  </div>
</div>

";
        // line 33
        if ((($context["gin_layout_paragraphs"] ?? null) == 1)) {
            // line 34
            yield "<style>
  .layout-node-form {
    --gin-lp-layout: \"";
            // line 36
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Layout"));
            yield "\";
    --gin-lp-content: \"";
            // line 37
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Content"));
            yield "\";
  }
</style>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["form", "gin_layout_paragraphs"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/gin/templates/node/node-edit-form.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  83 => 37,  79 => 36,  75 => 34,  73 => 33,  66 => 29,  59 => 25,  55 => 24,  48 => 20,  44 => 18,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/gin/templates/node/node-edit-form.html.twig", "/opt/drupal/web/themes/gin/templates/node/node-edit-form.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 33);
        static $filters = array("escape" => 20, "without" => 20, "t" => 36);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 'without', 't'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
