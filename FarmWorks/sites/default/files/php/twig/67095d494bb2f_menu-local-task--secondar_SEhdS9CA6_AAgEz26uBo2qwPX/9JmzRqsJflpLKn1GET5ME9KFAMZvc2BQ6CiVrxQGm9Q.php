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

/* profiles/farm/modules/core/ui/theme/templates/menu-local-task--secondary.html.twig */
class __TwigTemplate_9ac2e4a55196a5c7a0b26a37038f4152 extends Template
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
        // line 21
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("farm_ui_theme/menu_local_task"), "html", null, true);
        yield "
";
        // line 23
        $context["classes"] = ["tabs__tab", "js-tab", ((        // line 26
($context["is_active"] ?? null)) ? ("is-active") : ("")), ((        // line 27
($context["is_active"] ?? null)) ? ("js-active-tab") : (""))];
        // line 30
        yield "<li";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 30), 30, $this->source), "html", null, true);
        yield ">
  ";
        // line 31
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["link"] ?? null), 31, $this->source), "html", null, true);
        yield "
  ";
        // line 32
        if (($context["is_active"] ?? null)) {
            // line 33
            yield "    <button class=\"reset-appearance tabs__trigger\" aria-label=\"";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Tabs display toggle"));
            yield "\" data-drupal-nav-tabs-trigger>
      ";
            // line 34
            yield from             $this->loadTemplate("@claro/../images/src/hamburger-menu.svg", "profiles/farm/modules/core/ui/theme/templates/menu-local-task--secondary.html.twig", 34)->unwrap()->yield($context);
            // line 35
            yield "    </button>
  ";
        }
        // line 37
        yield "</li>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["is_active", "attributes", "link"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "profiles/farm/modules/core/ui/theme/templates/menu-local-task--secondary.html.twig";
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
        return array (  74 => 37,  70 => 35,  68 => 34,  63 => 33,  61 => 32,  57 => 31,  52 => 30,  50 => 27,  49 => 26,  48 => 23,  44 => 21,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "profiles/farm/modules/core/ui/theme/templates/menu-local-task--secondary.html.twig", "/opt/drupal/web/profiles/farm/modules/core/ui/theme/templates/menu-local-task--secondary.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 23, "if" => 32, "include" => 34);
        static $filters = array("escape" => 21, "t" => 33);
        static $functions = array("attach_library" => 21);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'include'],
                ['escape', 't'],
                ['attach_library'],
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
