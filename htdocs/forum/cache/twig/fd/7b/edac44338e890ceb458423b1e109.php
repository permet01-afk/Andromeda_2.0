<?php

/* acp_users_profile.html */
class __TwigTemplate_fd7bedac44338e890ceb458423b1e109 extends Twig_Template
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
        echo "\t<form id=\"user_profile\" method=\"post\" action=\"";
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\">

\t<fieldset>
\t\t<legend>";
        // line 4
        echo $this->env->getExtension('phpbb')->lang("USER_PROFILE");
        echo "</legend>
\t";
        // line 5
        // line 6
        echo "\t<dl>
\t\t<dt><label for=\"jabber\">";
        // line 7
        echo $this->env->getExtension('phpbb')->lang("UCP_JABBER");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t<dd><input type=\"email\" id=\"jabber\" name=\"jabber\" value=\"";
        // line 8
        echo (isset($context["JABBER"]) ? $context["JABBER"] : null);
        echo "\" /></dd>
\t</dl>
\t<dl>
\t\t<dt><label for=\"birthday\">";
        // line 11
        echo $this->env->getExtension('phpbb')->lang("BIRTHDAY");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("BIRTHDAY_EXPLAIN");
        echo "</span></dt>
\t\t<dd>";
        // line 12
        echo $this->env->getExtension('phpbb')->lang("DAY");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo " <select id=\"birthday\" name=\"bday_day\">";
        echo (isset($context["S_BIRTHDAY_DAY_OPTIONS"]) ? $context["S_BIRTHDAY_DAY_OPTIONS"] : null);
        echo "</select> ";
        echo $this->env->getExtension('phpbb')->lang("MONTH");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo " <select name=\"bday_month\">";
        echo (isset($context["S_BIRTHDAY_MONTH_OPTIONS"]) ? $context["S_BIRTHDAY_MONTH_OPTIONS"] : null);
        echo "</select> ";
        echo $this->env->getExtension('phpbb')->lang("YEAR");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo " <select name=\"bday_year\">";
        echo (isset($context["S_BIRTHDAY_YEAR_OPTIONS"]) ? $context["S_BIRTHDAY_YEAR_OPTIONS"] : null);
        echo "</select></dd>
\t</dl>
\t";
        // line 14
        // line 15
        echo "\t</fieldset>

\t";
        // line 17
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "profile_fields"))) {
            // line 18
            echo "\t\t<fieldset>
\t\t\t<legend>";
            // line 19
            echo $this->env->getExtension('phpbb')->lang("USER_CUSTOM_PROFILE_FIELDS");
            echo "</legend>
\t\t";
            // line 20
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "profile_fields"));
            foreach ($context['_seq'] as $context["_key"] => $context["profile_fields"]) {
                // line 21
                echo "\t\t<dl>
\t\t\t<dt><label";
                // line 22
                if ($this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "FIELD_ID")) {
                    echo " for=\"";
                    echo $this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "FIELD_ID");
                    echo "\"";
                }
                echo ">";
                echo $this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "LANG_NAME");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label>";
                if ($this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "LANG_EXPLAIN")) {
                    echo "<br /><span>";
                    echo $this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "LANG_EXPLAIN");
                    echo "</span>";
                }
                echo "</dt>
\t\t\t<dd>";
                // line 23
                echo $this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "FIELD");
                echo "</dd>
\t\t\t";
                // line 24
                if ($this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "ERROR")) {
                    // line 25
                    echo "\t\t\t\t<dd><span class=\"small\" style=\"color: red;\">";
                    echo $this->getAttribute((isset($context["profile_fields"]) ? $context["profile_fields"] : null), "ERROR");
                    echo "</span></dd>
\t\t\t";
                }
                // line 27
                echo "\t\t</dl>
\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['profile_fields'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            echo "\t\t</fieldset>
\t";
        }
        // line 31
        echo "\t";
        // line 32
        echo "\t<fieldset class=\"quick\">
\t\t<input class=\"button1\" type=\"submit\" name=\"update\" value=\"";
        // line 33
        echo $this->env->getExtension('phpbb')->lang("SUBMIT");
        echo "\" />
\t\t";
        // line 34
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t</fieldset>
\t</form>
";
    }

    public function getTemplateName()
    {
        return "acp_users_profile.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  140 => 34,  136 => 33,  133 => 32,  131 => 31,  127 => 29,  120 => 27,  114 => 25,  112 => 24,  108 => 23,  91 => 22,  88 => 21,  84 => 20,  80 => 19,  77 => 18,  75 => 17,  71 => 15,  70 => 14,  52 => 12,  45 => 11,  39 => 8,  34 => 7,  31 => 6,  30 => 5,  26 => 4,  23 => 2,  19 => 1,);
    }
}
