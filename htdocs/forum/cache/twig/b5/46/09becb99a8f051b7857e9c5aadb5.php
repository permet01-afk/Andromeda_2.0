<?php

/* timezone_option.html */
class __TwigTemplate_b54609becb99a8f051b7857e9c5aadb5 extends Twig_Template
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
        echo "<dl>
\t<dt><label for=\"timezone\">";
        // line 2
        echo $this->env->getExtension('phpbb')->lang("BOARD_TIMEZONE");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t";
        // line 3
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "timezone_date"))) {
            // line 4
            echo "\t<dd id=\"tz_select_date\" style=\"display: none;\">
\t\t<select name=\"tz_date\" id=\"tz_date\" class=\"autowidth tz_select\">
\t\t\t<option value=\"\">";
            // line 6
            echo $this->env->getExtension('phpbb')->lang("SELECT_CURRENT_TIME");
            echo "</option>
\t\t\t";
            // line 7
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "timezone_date"));
            foreach ($context['_seq'] as $context["_key"] => $context["timezone_date"]) {
                // line 8
                echo "\t\t\t\t<option value=\"";
                echo $this->getAttribute((isset($context["timezone_date"]) ? $context["timezone_date"] : null), "VALUE");
                echo "\"";
                if ($this->getAttribute((isset($context["timezone_date"]) ? $context["timezone_date"] : null), "SELECTED")) {
                    echo " selected=\"selected\"";
                }
                echo ">";
                echo $this->getAttribute((isset($context["timezone_date"]) ? $context["timezone_date"] : null), "TITLE");
                echo "</option>
\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['timezone_date'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 10
            echo "\t\t</select>
\t\t<input type=\"button\" id=\"tz_select_date_suggest\" class=\"button2\" style=\"display: none;\" timezone-preselect=\"";
            // line 11
            if ((isset($context["S_TZ_PRESELECT"]) ? $context["S_TZ_PRESELECT"] : null)) {
                echo "true";
            } else {
                echo "false";
            }
            echo "\" data-l-suggestion=\"";
            echo $this->env->getExtension('phpbb')->lang("TIMEZONE_DATE_SUGGESTION");
            echo "\" value=\"";
            echo $this->env->getExtension('phpbb')->lang("TIMEZONE_DATE_SUGGESTION");
            echo "\" />
\t</dd>
\t";
        }
        // line 14
        echo "\t<dd>
\t\t<select name=\"tz\" id=\"timezone\" class=\"autowidth tz_select\">
\t\t\t<option value=\"\">";
        // line 16
        echo $this->env->getExtension('phpbb')->lang("SELECT_TIMEZONE");
        echo "</option>
\t\t\t";
        // line 17
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "timezone_select"));
        foreach ($context['_seq'] as $context["_key"] => $context["timezone_select"]) {
            // line 18
            echo "\t\t\t<optgroup label=\"";
            echo $this->getAttribute((isset($context["timezone_select"]) ? $context["timezone_select"] : null), "LABEL");
            echo "\" data-tz-value=\"";
            echo $this->getAttribute((isset($context["timezone_select"]) ? $context["timezone_select"] : null), "VALUE");
            echo "\">
\t\t\t\t";
            // line 19
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["timezone_select"]) ? $context["timezone_select"] : null), "timezone_options"));
            foreach ($context['_seq'] as $context["_key"] => $context["timezone_options"]) {
                // line 20
                echo "\t\t\t\t<option title=\"";
                echo $this->getAttribute((isset($context["timezone_options"]) ? $context["timezone_options"] : null), "TITLE");
                echo "\" value=\"";
                echo $this->getAttribute((isset($context["timezone_options"]) ? $context["timezone_options"] : null), "VALUE");
                echo "\"";
                if ($this->getAttribute((isset($context["timezone_options"]) ? $context["timezone_options"] : null), "SELECTED")) {
                    echo " selected=\"selected\"";
                }
                echo ">";
                echo $this->getAttribute((isset($context["timezone_options"]) ? $context["timezone_options"] : null), "LABEL");
                echo "</option>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['timezone_options'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 22
            echo "\t\t\t</optgroup>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['timezone_select'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 24
        echo "\t\t</select>

\t\t";
        // line 26
        $asset_file = "timezone.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            $asset->add_assets_version('2');
            $asset_file = $asset->get_url();
            }
        }
        $context['definition']->append('SCRIPTS', '<script type="text/javascript" src="' . $asset_file. '"></script>

');
        // line 27
        echo "\t</dd>
</dl>
";
    }

    public function getTemplateName()
    {
        return "timezone_option.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  124 => 26,  120 => 24,  113 => 22,  96 => 20,  85 => 18,  81 => 17,  77 => 16,  73 => 14,  59 => 11,  37 => 7,  33 => 6,  29 => 4,  27 => 3,  22 => 2,  346 => 124,  310 => 91,  306 => 90,  297 => 84,  293 => 83,  287 => 82,  279 => 76,  278 => 75,  273 => 73,  267 => 70,  258 => 67,  255 => 66,  242 => 65,  236 => 62,  231 => 61,  228 => 60,  225 => 59,  219 => 56,  214 => 55,  211 => 54,  208 => 53,  197 => 49,  189 => 48,  181 => 47,  175 => 45,  172 => 44,  169 => 43,  158 => 39,  150 => 38,  142 => 36,  139 => 27,  137 => 34,  127 => 31,  119 => 30,  111 => 28,  100 => 24,  92 => 19,  86 => 21,  75 => 17,  67 => 16,  61 => 14,  58 => 13,  56 => 10,  50 => 11,  41 => 8,  34 => 3,  31 => 2,  19 => 1,);
    }
}
