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

/* profiles/farm/modules/core/ui/theme/templates/comment.html.twig */
class __TwigTemplate_3242721aabcf137dd338817141640d84 extends Template
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
        // line 69
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("farm_ui_theme/comment"), "html", null, true);
        yield "
";
        // line 70
        if (($context["threaded"] ?? null)) {
            // line 71
            yield "  ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("claro/classy.indented"), "html", null, true);
            yield "
";
        }
        // line 74
        $context["classes"] = ["comment", "js-comment", (((        // line 77
($context["status"] ?? null) != "published")) ? (($context["status"] ?? null)) : ("")), ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,         // line 78
($context["comment"] ?? null), "owner", [], "any", false, false, true, 78), "anonymous", [], "any", false, false, true, 78)) ? ("by-anonymous") : ("")), (((        // line 79
($context["author_id"] ?? null) && (($context["author_id"] ?? null) == CoreExtension::getAttribute($this->env, $this->source, ($context["commented_entity"] ?? null), "getOwnerId", [], "method", false, false, true, 79)))) ? ((("by-" . $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["commented_entity"] ?? null), "getEntityTypeId", [], "method", false, false, true, 79), 79, $this->source)) . "-author")) : (""))];
        // line 82
        yield "<article";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 82), 82, $this->source), "html", null, true);
        yield ">
  ";
        // line 88
        yield "  <mark class=\"hidden\" data-comment-timestamp=\"";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["new_indicator_timestamp"] ?? null), 88, $this->source), "html", null, true);
        yield "\"></mark>

  ";
        // line 90
        if (($context["submitted"] ?? null)) {
            // line 91
            yield "    <footer class=\"comment__meta\">
      <p class=\"comment__submitted\">";
            // line 92
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["submitted"] ?? null), 92, $this->source), "html", null, true);
            yield "</p>

      ";
            // line 99
            yield "      ";
            if (($context["parent"] ?? null)) {
                // line 100
                yield "        <p class=\"parent visually-hidden\">";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["parent"] ?? null), 100, $this->source), "html", null, true);
                yield "</p>
      ";
            }
            // line 102
            yield "    </footer>
  ";
        }
        // line 104
        yield "
  <div";
        // line 105
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", ["content"], "method", false, false, true, 105), 105, $this->source), "html", null, true);
        yield ">
    ";
        // line 106
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 106, $this->source), "html", null, true);
        yield "
  </div>
</article>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["threaded", "status", "comment", "author_id", "commented_entity", "attributes", "new_indicator_timestamp", "submitted", "parent", "content_attributes", "content"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "profiles/farm/modules/core/ui/theme/templates/comment.html.twig";
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
        return array (  102 => 106,  98 => 105,  95 => 104,  91 => 102,  85 => 100,  82 => 99,  77 => 92,  74 => 91,  72 => 90,  66 => 88,  61 => 82,  59 => 79,  58 => 78,  57 => 77,  56 => 74,  50 => 71,  48 => 70,  44 => 69,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "profiles/farm/modules/core/ui/theme/templates/comment.html.twig", "/opt/drupal/web/profiles/farm/modules/core/ui/theme/templates/comment.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 70, "set" => 74);
        static $filters = array("escape" => 69);
        static $functions = array("attach_library" => 69);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set'],
                ['escape'],
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
