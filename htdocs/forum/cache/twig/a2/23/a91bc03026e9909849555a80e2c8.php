<?php

/* timezone_option.html */
class __TwigTemplate_a223a91bc03026e9909849555a80e2c8 extends Twig_Template
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
                echo "\t\t\t<option value=\"";
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
\t</dd>
\t";
        }
        // line 13
        echo "\t<dd>
\t\t<select name=\"tz\" id=\"timezone\" class=\"autowidth tz_select\">
\t\t\t<option value=\"\">";
        // line 15
        echo $this->env->getExtension('phpbb')->lang("SELECT_TIMEZONE");
        echo "</option>
\t\t\t";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "timezone_select"));
        foreach ($context['_seq'] as $context["_key"] => $context["timezone_select"]) {
            // line 17
            echo "\t\t\t<optgroup label=\"";
            echo $this->getAttribute((isset($context["timezone_select"]) ? $context["timezone_select"] : null), "LABEL");
            echo "\" data-tz-value=\"";
            echo $this->getAttribute((isset($context["timezone_select"]) ? $context["timezone_select"] : null), "VALUE");
            echo "\">
\t\t\t\t";
            // line 18
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["timezone_select"]) ? $context["timezone_select"] : null), "timezone_options"));
            foreach ($context['_seq'] as $context["_key"] => $context["timezone_options"]) {
                // line 19
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
            // line 21
            echo "\t\t\t</optgroup>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['timezone_select'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "\t\t</select>

\t\t";
        // line 25
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
        // line 26
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
        return array (  127 => 26,  112 => 25,  108 => 23,  101 => 21,  84 => 19,  80 => 18,  73 => 17,  65 => 15,  61 => 13,  56 => 10,  41 => 8,  37 => 7,  33 => 6,  29 => 4,  27 => 3,  22 => 2,  589 => 148,  585 => 147,  582 => 146,  581 => 145,  578 => 144,  577 => 143,  572 => 141,  567 => 140,  561 => 137,  556 => 136,  550 => 133,  545 => 132,  539 => 129,  534 => 128,  528 => 125,  523 => 124,  517 => 121,  512 => 120,  502 => 117,  494 => 116,  489 => 115,  479 => 112,  471 => 111,  466 => 110,  456 => 107,  448 => 106,  443 => 105,  433 => 102,  425 => 101,  420 => 100,  410 => 97,  402 => 96,  397 => 95,  387 => 92,  379 => 91,  374 => 90,  371 => 89,  370 => 88,  366 => 87,  361 => 84,  360 => 83,  351 => 81,  343 => 80,  338 => 79,  328 => 76,  320 => 75,  315 => 74,  305 => 71,  297 => 70,  292 => 69,  282 => 66,  274 => 65,  269 => 64,  266 => 63,  265 => 62,  261 => 61,  256 => 58,  255 => 57,  246 => 55,  242 => 54,  235 => 53,  232 => 52,  220 => 51,  215 => 49,  210 => 48,  204 => 45,  199 => 44,  189 => 41,  181 => 40,  176 => 39,  166 => 36,  155 => 35,  147 => 34,  140 => 33,  130 => 30,  122 => 29,  117 => 28,  107 => 25,  99 => 24,  92 => 23,  82 => 20,  74 => 19,  69 => 16,  59 => 15,  51 => 14,  46 => 13,  43 => 12,  42 => 11,  38 => 10,  35 => 9,  34 => 8,  30 => 7,  23 => 3,  19 => 1,);
    }
}
