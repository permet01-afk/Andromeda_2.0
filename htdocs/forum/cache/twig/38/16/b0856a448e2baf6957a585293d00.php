<?php

/* ucp_avatar_options.html */
class __TwigTemplate_3816b0856a448e2baf6957a585293d00 extends Twig_Template
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
        echo "<div class=\"panel\">
\t<div class=\"inner\">
\t";
        // line 3
        if ((!(isset($context["S_AVATARS_ENABLED"]) ? $context["S_AVATARS_ENABLED"] : null))) {
            // line 4
            echo "\t\t<p>";
            echo $this->env->getExtension('phpbb')->lang("AVATAR_FEATURES_DISABLED");
            echo "</p>
\t";
        }
        // line 6
        echo "
\t<fieldset>
\t";
        // line 8
        if ((isset($context["ERROR"]) ? $context["ERROR"] : null)) {
            echo "<p class=\"error\">";
            echo (isset($context["ERROR"]) ? $context["ERROR"] : null);
            echo "</p>";
        }
        // line 9
        echo "\t\t<dl>
\t\t\t<dt><label>";
        // line 10
        echo $this->env->getExtension('phpbb')->lang("CURRENT_IMAGE");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("AVATAR_EXPLAIN");
        echo "</span></dt>
\t\t\t<dd>";
        // line 11
        if ((isset($context["AVATAR"]) ? $context["AVATAR"] : null)) {
            echo (isset($context["AVATAR"]) ? $context["AVATAR"] : null);
        } else {
            echo "<img src=\"";
            echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
            echo "/images/no_avatar.png\" alt=\"\" />";
        }
        echo "</dd>
\t\t\t<dd><label for=\"avatar_delete\"><input type=\"checkbox\" name=\"avatar_delete\" id=\"avatar_delete\" /> ";
        // line 12
        echo $this->env->getExtension('phpbb')->lang("DELETE_AVATAR");
        echo "</label></dd>
\t\t</dl>
\t</fieldset>
\t<h3>";
        // line 15
        echo $this->env->getExtension('phpbb')->lang("AVATAR_SELECT");
        echo "</h3>
\t<fieldset>
\t\t<dl>
\t\t\t<dt><label>";
        // line 18
        echo $this->env->getExtension('phpbb')->lang("AVATAR_TYPE");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t\t<dd><select name=\"avatar_driver\" id=\"avatar_driver\" data-togglable-settings=\"true\">
\t\t\t\t";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "avatar_drivers"));
        foreach ($context['_seq'] as $context["_key"] => $context["avatar_drivers"]) {
            // line 21
            echo "\t\t\t\t<option value=\"";
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "DRIVER");
            echo "\"";
            if ($this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "SELECTED")) {
                echo " selected=\"selected\"";
            }
            echo " data-toggle-setting=\"#avatar_option_";
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "DRIVER");
            echo "\">";
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "L_TITLE");
            echo "</option>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['avatar_drivers'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "\t\t\t</select></dd>
\t\t</dl>
\t</fieldset>
\t<div id=\"avatar_options\">
";
        // line 27
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "avatar_drivers"));
        foreach ($context['_seq'] as $context["_key"] => $context["avatar_drivers"]) {
            // line 28
            echo "\t<div id=\"avatar_option_";
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "DRIVER");
            echo "\">
\t<noscript>
\t<h3 class=\"avatar_section_header\">";
            // line 30
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "L_TITLE");
            echo "</h3>
\t</noscript>
\t<p>";
            // line 32
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "L_EXPLAIN");
            echo "</p>

\t<fieldset>
\t";
            // line 35
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "OUTPUT");
            echo "
\t</fieldset>
\t</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['avatar_drivers'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "\t</div>
";
        // line 40
        if ((!(isset($context["S_GROUP_MANAGE"]) ? $context["S_GROUP_MANAGE"] : null))) {
            // line 41
            echo "\t<fieldset class=\"submit-buttons\">
\t\t<input type=\"reset\" value=\"";
            // line 42
            echo $this->env->getExtension('phpbb')->lang("RESET");
            echo "\" name=\"reset\" class=\"button2\" /> &nbsp;
\t\t<input type=\"submit\" name=\"submit\" value=\"";
            // line 43
            echo $this->env->getExtension('phpbb')->lang("SUBMIT");
            echo "\" class=\"button1\" />
\t</fieldset>
";
        }
        // line 46
        echo "\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "ucp_avatar_options.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  155 => 46,  149 => 43,  145 => 42,  142 => 41,  140 => 40,  137 => 39,  127 => 35,  121 => 32,  116 => 30,  110 => 28,  106 => 27,  100 => 23,  83 => 21,  79 => 20,  73 => 18,  67 => 15,  51 => 11,  44 => 10,  25 => 4,  23 => 3,  71 => 13,  65 => 10,  61 => 12,  58 => 8,  46 => 7,  41 => 9,  34 => 3,  31 => 6,  38 => 8,  35 => 8,  33 => 6,  27 => 3,  22 => 2,  19 => 1,);
    }
}
