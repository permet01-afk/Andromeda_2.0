<?php

/* mcp_whois.html */
class __TwigTemplate_82c3288f4dd101e72cec26b114fdb78b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $location = "mcp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("mcp_header.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "<h2>";
        echo $this->env->getExtension('phpbb')->lang("WHOIS");
        echo "</h2>

<div class=\"panel\">
\t<div class=\"inner\">
\t
\t\t<p><a class=\"arrow-";
        // line 7
        echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
        echo "\" href=\"";
        echo (isset($context["U_RETURN_POST"]) ? $context["U_RETURN_POST"] : null);
        echo "\">";
        echo $this->env->getExtension('phpbb')->lang("RETURN_POST");
        echo "</a></p>
\t\t<div class=\"postbody\"><div class=\"content\">
\t\t\t<pre>";
        // line 9
        echo (isset($context["WHOIS"]) ? $context["WHOIS"] : null);
        echo "</pre>
\t\t</div></div>
\t\t<p><a class=\"arrow-";
        // line 11
        echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
        echo "\" href=\"";
        echo (isset($context["U_RETURN_POST"]) ? $context["U_RETURN_POST"] : null);
        echo "\">";
        echo $this->env->getExtension('phpbb')->lang("RETURN_POST");
        echo "</a></p>
\t
\t</div>
</div>

";
        // line 16
        $location = "mcp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("mcp_footer.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "mcp_whois.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 16,  54 => 11,  49 => 9,  40 => 7,  31 => 2,  19 => 1,);
    }
}
