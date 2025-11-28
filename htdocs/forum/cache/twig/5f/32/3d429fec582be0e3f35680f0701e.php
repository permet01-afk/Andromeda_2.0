<?php

/* acp_users_avatar.html */
class __TwigTemplate_5f323d429fec582be0e3f35680f0701e extends Twig_Template
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
        echo "\t<form id=\"avatar_settings\" method=\"post\" action=\"";
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\" enctype=\"multipart/form-data\">

\t<fieldset>
\t\t<legend>";
        // line 4
        echo $this->env->getExtension('phpbb')->lang("ACP_USER_AVATAR");
        echo "</legend>
\t";
        // line 5
        if ((isset($context["ERROR"]) ? $context["ERROR"] : null)) {
            echo "<p class=\"error\">";
            echo (isset($context["ERROR"]) ? $context["ERROR"] : null);
            echo "</p>";
        }
        // line 6
        echo "\t\t<dl>
\t\t\t<dt><label>";
        // line 7
        echo $this->env->getExtension('phpbb')->lang("CURRENT_IMAGE");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("AVATAR_EXPLAIN");
        echo "</span></dt>
\t\t\t<dd>";
        // line 8
        echo (isset($context["AVATAR"]) ? $context["AVATAR"] : null);
        echo "</dd>
\t\t\t<dd><label for=\"avatar_delete\"><input type=\"checkbox\" name=\"avatar_delete\" id=\"avatar_delete\" /> ";
        // line 9
        echo $this->env->getExtension('phpbb')->lang("DELETE_AVATAR");
        echo "</label></dd>
\t\t</dl>
\t</fieldset>
\t<fieldset>
\t\t<legend>";
        // line 13
        echo $this->env->getExtension('phpbb')->lang("AVATAR_SELECT");
        echo "</legend>
\t\t<dl>
\t\t\t<dt><label>";
        // line 15
        echo $this->env->getExtension('phpbb')->lang("AVATAR_TYPE");
        echo "</label></dt>
\t\t\t<dd><select name=\"avatar_driver\" id=\"avatar_driver\" data-togglable-settings=\"true\">
\t\t\t\t";
        // line 17
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "avatar_drivers"));
        foreach ($context['_seq'] as $context["_key"] => $context["avatar_drivers"]) {
            // line 18
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
        // line 20
        echo "\t\t\t</select></dd>
\t\t</dl>
\t\t<div id=\"avatar_options\">
\t\t";
        // line 23
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "avatar_drivers"));
        foreach ($context['_seq'] as $context["_key"] => $context["avatar_drivers"]) {
            // line 24
            echo "\t\t\t<div id=\"avatar_option_";
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "DRIVER");
            echo "\">
\t\t\t\t<p>";
            // line 25
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "L_EXPLAIN");
            echo "</p>
\t\t\t\t";
            // line 26
            echo $this->getAttribute((isset($context["avatar_drivers"]) ? $context["avatar_drivers"] : null), "OUTPUT");
            echo "
\t\t\t</div>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['avatar_drivers'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "\t\t</div>
\t</fieldset>

\t<fieldset class=\"quick\">
\t\t<input type=\"submit\" name=\"update\" value=\"";
        // line 33
        echo $this->env->getExtension('phpbb')->lang("SUBMIT");
        echo "\" class=\"button1\" />
\t";
        // line 34
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t</fieldset>
\t</form>
";
    }

    public function getTemplateName()
    {
        return "acp_users_avatar.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  125 => 34,  121 => 33,  115 => 29,  106 => 26,  102 => 25,  97 => 24,  93 => 23,  88 => 20,  71 => 18,  67 => 17,  62 => 15,  57 => 13,  50 => 9,  46 => 8,  39 => 7,  36 => 6,  30 => 5,  26 => 4,  19 => 1,);
    }
}
