<?php

/* acp_users_feedback.html */
class __TwigTemplate_96d8a46cbb57c501e116a67b44a5617b extends Twig_Template
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
        echo "\t<form id=\"list\" method=\"post\" action=\"";
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\">

\t<div class=\"pagination\">
\t";
        // line 4
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
            // line 5
            echo "\t\t";
            $location = "pagination.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->env->loadTemplate("pagination.html")->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            // line 6
            echo "\t";
        }
        // line 7
        echo "\t</div>

\t";
        // line 9
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "log"))) {
            // line 10
            echo "\t<table class=\"table1 zebra-table\">
\t<thead>
\t<tr>
\t\t<th>";
            // line 13
            echo $this->env->getExtension('phpbb')->lang("REPORT_BY");
            echo "</th>
\t\t<th>";
            // line 14
            echo $this->env->getExtension('phpbb')->lang("IP");
            echo "</th>
\t\t<th>";
            // line 15
            echo $this->env->getExtension('phpbb')->lang("TIME");
            echo "</th>
\t\t<th>";
            // line 16
            echo $this->env->getExtension('phpbb')->lang("FEEDBACK");
            echo "</th>
\t\t<th>";
            // line 17
            echo $this->env->getExtension('phpbb')->lang("MARK");
            echo "</th>
\t</tr>
\t</thead>
\t<tbody>
\t";
            // line 21
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "log"));
            foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                // line 22
                echo "\t\t<tr>
\t\t\t<td>";
                // line 23
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "USERNAME");
                echo "</td>
\t\t\t<td style=\"text-align: center;\">";
                // line 24
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "IP");
                echo "</td>
\t\t\t<td style=\"text-align: center;\">";
                // line 25
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "DATE");
                echo "</td>
\t\t\t<td>
\t\t\t\t";
                // line 27
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "ACTION");
                echo "
\t\t\t\t";
                // line 28
                if ($this->getAttribute((isset($context["log"]) ? $context["log"] : null), "DATA")) {
                    echo "<br />&#187; <span class=\"gensmall\">[ ";
                    echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "DATA");
                    echo " ]</span>";
                }
                // line 29
                echo "\t\t\t</td>
\t\t\t<td style=\"text-align: center;\"><input type=\"checkbox\" class=\"radio\" name=\"mark[]\" value=\"";
                // line 30
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "ID");
                echo "\" /></td>
\t\t</tr>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 33
            echo "\t</tbody>
\t</table>
\t";
        } else {
            // line 36
            echo "\t\t<div class=\"errorbox\">
\t\t\t<p>";
            // line 37
            echo $this->env->getExtension('phpbb')->lang("NO_ENTRIES");
            echo "</p>
\t\t</div>
\t";
        }
        // line 40
        echo "\t
\t<fieldset class=\"display-options\">
\t\t";
        // line 42
        echo $this->env->getExtension('phpbb')->lang("DISPLAY_LOG");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo " &nbsp;";
        echo (isset($context["S_LIMIT_DAYS"]) ? $context["S_LIMIT_DAYS"] : null);
        echo "&nbsp;";
        echo $this->env->getExtension('phpbb')->lang("SORT_BY");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo " ";
        echo (isset($context["S_SORT_KEY"]) ? $context["S_SORT_KEY"] : null);
        echo " ";
        echo (isset($context["S_SORT_DIR"]) ? $context["S_SORT_DIR"] : null);
        echo "
\t\t<input class=\"button2\" type=\"submit\" value=\"";
        // line 43
        echo $this->env->getExtension('phpbb')->lang("GO");
        echo "\" name=\"sort\" />
\t</fieldset>
\t<hr />
\t<div class=\"pagination\">
\t";
        // line 47
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
            // line 48
            echo "\t\t";
            $location = "pagination.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->env->loadTemplate("pagination.html")->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            // line 49
            echo "\t";
        }
        // line 50
        echo "\t</div>

\t";
        // line 52
        if ((isset($context["S_CLEARLOGS"]) ? $context["S_CLEARLOGS"] : null)) {
            // line 53
            echo "\t\t<fieldset class=\"quick\">
\t\t\t<input class=\"button2\" type=\"submit\" name=\"delall\" value=\"";
            // line 54
            echo $this->env->getExtension('phpbb')->lang("DELETE_ALL");
            echo "\" />&nbsp;
\t\t\t<input class=\"button2\" type=\"submit\" name=\"delmarked\" value=\"";
            // line 55
            echo $this->env->getExtension('phpbb')->lang("DELETE_MARKED");
            echo "\" />
\t\t\t<p class=\"small\"><a href=\"#\" onclick=\"marklist('list', 'mark', true);\">";
            // line 56
            echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"marklist('list', 'mark', false);\">";
            echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
            echo "</a></p>
\t\t</fieldset>
\t";
        }
        // line 59
        echo "
\t<h1>";
        // line 60
        echo $this->env->getExtension('phpbb')->lang("ADD_FEEDBACK");
        echo "</h1>

\t<p>";
        // line 62
        echo $this->env->getExtension('phpbb')->lang("ADD_FEEDBACK_EXPLAIN");
        echo "</p>

\t<fieldset>
\t\t<legend>";
        // line 65
        echo $this->env->getExtension('phpbb')->lang("ACP_USER_FEEDBACK");
        echo "</legend>
\t\t<dl>
\t\t\t<dd class=\"full\"><textarea name=\"message\" id=\"message\" rows=\"10\" cols=\"76\"></textarea></dd>
\t\t</dl>
\t</fieldset>

\t<fieldset class=\"quick\">
\t\t<input class=\"button1\" type=\"submit\" name=\"update\" value=\"";
        // line 72
        echo $this->env->getExtension('phpbb')->lang("SUBMIT");
        echo "\" />
\t</fieldset>
\t";
        // line 74
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t</form>
";
    }

    public function getTemplateName()
    {
        return "acp_users_feedback.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  231 => 74,  226 => 72,  216 => 65,  210 => 62,  205 => 60,  202 => 59,  194 => 56,  190 => 55,  186 => 54,  183 => 53,  181 => 52,  177 => 50,  174 => 49,  161 => 48,  159 => 47,  152 => 43,  138 => 42,  134 => 40,  128 => 37,  125 => 36,  120 => 33,  111 => 30,  108 => 29,  102 => 28,  98 => 27,  93 => 25,  89 => 24,  85 => 23,  82 => 22,  78 => 21,  71 => 17,  67 => 16,  63 => 15,  59 => 14,  55 => 13,  50 => 10,  48 => 9,  44 => 7,  41 => 6,  28 => 5,  26 => 4,  19 => 1,);
    }
}
