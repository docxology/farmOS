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

/* core/modules/layout_discovery/layouts/twocol/layout--twocol.html.twig */
class __TwigTemplate_a17f9f7022922afdecacae3ac59de472 extends Template
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
        // line 15
        $context["classes"] = ["layout", "layout--twocol"];
        // line 20
        if (($context["content"] ?? null)) {
            // line 21
            yield "  <div";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 21), 21, $this->source), "html", null, true);
            yield ">
    ";
            // line 22
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "top", [], "any", false, false, true, 22)) {
                // line 23
                yield "      <div ";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["region_attributes"] ?? null), "top", [], "any", false, false, true, 23), "addClass", ["layout__region", "layout__region--top"], "method", false, false, true, 23), 23, $this->source), "html", null, true);
                yield ">
        ";
                // line 24
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "top", [], "any", false, false, true, 24), 24, $this->source), "html", null, true);
                yield "
      </div>
    ";
            }
            // line 27
            yield "
    ";
            // line 28
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "first", [], "any", false, false, true, 28)) {
                // line 29
                yield "      <div ";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["region_attributes"] ?? null), "first", [], "any", false, false, true, 29), "addClass", ["layout__region", "layout__region--first"], "method", false, false, true, 29), 29, $this->source), "html", null, true);
                yield ">
        ";
                // line 30
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "first", [], "any", false, false, true, 30), 30, $this->source), "html", null, true);
                yield "
      </div>
    ";
            }
            // line 33
            yield "
    ";
            // line 34
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "second", [], "any", false, false, true, 34)) {
                // line 35
                yield "      <div ";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["region_attributes"] ?? null), "second", [], "any", false, false, true, 35), "addClass", ["layout__region", "layout__region--second"], "method", false, false, true, 35), 35, $this->source), "html", null, true);
                yield ">
        ";
                // line 36
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "second", [], "any", false, false, true, 36), 36, $this->source), "html", null, true);
                yield "
      </div>
    ";
            }
            // line 39
            yield "
    ";
            // line 40
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "bottom", [], "any", false, false, true, 40)) {
                // line 41
                yield "      <div ";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["region_attributes"] ?? null), "bottom", [], "any", false, false, true, 41), "addClass", ["layout__region", "layout__region--bottom"], "method", false, false, true, 41), 41, $this->source), "html", null, true);
                yield ">
        ";
                // line 42
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "bottom", [], "any", false, false, true, 42), 42, $this->source), "html", null, true);
                yield "
      </div>
    ";
            }
            // line 45
            yield "  </div>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["content", "attributes", "region_attributes"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/modules/layout_discovery/layouts/twocol/layout--twocol.html.twig";
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
        return array (  114 => 45,  108 => 42,  103 => 41,  101 => 40,  98 => 39,  92 => 36,  87 => 35,  85 => 34,  82 => 33,  76 => 30,  71 => 29,  69 => 28,  66 => 27,  60 => 24,  55 => 23,  53 => 22,  48 => 21,  46 => 20,  44 => 15,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/modules/layout_discovery/layouts/twocol/layout--twocol.html.twig", "/opt/drupal/web/core/modules/layout_discovery/layouts/twocol/layout--twocol.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 15, "if" => 20);
        static $filters = array("escape" => 21);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape'],
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
