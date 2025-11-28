<?php

/* acp_groups_position.html */
class __TwigTemplate_94bbb5722de50ba51015a32284c5f440 extends Twig_Template
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
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("overall_header.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<a id=\"maincontent\"></a>

\t<h1>";
        // line 5
        echo $this->env->getExtension('phpbb')->lang("MANAGE_LEGEND");
        echo "</h1>

\t<form id=\"legend_settings\" method=\"post\" action=\"";
        // line 7
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\"";
        if ((isset($context["S_CAN_UPLOAD"]) ? $context["S_CAN_UPLOAD"] : null)) {
            echo " enctype=\"multipart/form-data\"";
        }
        echo ">

\t<fieldset>
\t\t<legend>";
        // line 10
        echo $this->env->getExtension('phpbb')->lang("LEGEND_SETTINGS");
        echo "</legend>
\t\t<dl>
\t\t\t<dt><label for=\"legend_sort_groupname\">";
        // line 12
        echo $this->env->getExtension('phpbb')->lang("LEGEND_SORT_GROUPNAME");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("LEGEND_SORT_GROUPNAME_EXPLAIN");
        echo "</span></dt>
\t\t\t<dd>
\t\t\t\t<label><input type=\"radio\" name=\"legend_sort_groupname\" class=\"radio\" value=\"1\"";
        // line 14
        if ((isset($context["LEGEND_SORT_GROUPNAME"]) ? $context["LEGEND_SORT_GROUPNAME"] : null)) {
            echo " checked=\"checked\"";
        }
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("YES");
        echo "</label>
\t\t\t\t<label><input type=\"radio\" name=\"legend_sort_groupname\" class=\"radio\" value=\"0\"";
        // line 15
        if ((!(isset($context["LEGEND_SORT_GROUPNAME"]) ? $context["LEGEND_SORT_GROUPNAME"] : null))) {
            echo " checked=\"checked\"";
        }
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("NO");
        echo "</label>
\t\t\t</dd>
\t\t</dl>

\t<p class=\"submit-buttons\">
\t\t<input class=\"button1\" type=\"submit\" name=\"update\" value=\"";
        // line 20
        echo $this->env->getExtension('phpbb')->lang("SUBMIT");
        echo "\" />&nbsp;
\t\t<input class=\"button2\" type=\"reset\" name=\"reset\" value=\"";
        // line 21
        echo $this->env->getExtension('phpbb')->lang("RESET");
        echo "\" />
\t\t<input type=\"hidden\" name=\"action\" value=\"set_config_legend\" />
\t\t";
        // line 23
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t</p>
\t</fieldset>
\t</form>

\t<p>";
        // line 28
        echo $this->env->getExtension('phpbb')->lang("LEGEND_EXPLAIN");
        echo "</p>

\t<table class=\"table1\">
\t\t<col class=\"col1\" /><col class=\"col2\" /><col class=\"col2\" />
\t<thead>
\t<tr>
\t\t<th style=\"width: 50%\">";
        // line 34
        echo $this->env->getExtension('phpbb')->lang("GROUP");
        echo "</th>
\t\t<th>";
        // line 35
        echo $this->env->getExtension('phpbb')->lang("GROUP_TYPE");
        echo "</th>
\t\t<th>";
        // line 36
        echo $this->env->getExtension('phpbb')->lang("ACTION");
        echo "</th>
\t</tr>
\t</thead>
\t<tbody>
\t";
        // line 40
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "legend"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["legend"]) {
            // line 41
            echo "\t\t<tr>
\t\t\t<td><strong";
            // line 42
            if ($this->getAttribute((isset($context["legend"]) ? $context["legend"] : null), "GROUP_COLOUR")) {
                echo " style=\"color: ";
                echo $this->getAttribute((isset($context["legend"]) ? $context["legend"] : null), "GROUP_COLOUR");
                echo "\"";
            }
            echo ">";
            echo $this->getAttribute((isset($context["legend"]) ? $context["legend"] : null), "GROUP_NAME");
            echo "</strong></td>
\t\t\t<td style=\"text-align: center;\">";
            // line 43
            echo $this->getAttribute((isset($context["legend"]) ? $context["legend"] : null), "GROUP_TYPE");
            echo "</td>
\t\t\t<td class=\"actions\">
\t\t\t\t<span class=\"up-disabled\" style=\"display: none;\">";
            // line 45
            echo (isset($context["ICON_MOVE_UP_DISABLED"]) ? $context["ICON_MOVE_UP_DISABLED"] : null);
            echo "</span>
\t\t\t\t<span class=\"up\"><a href=\"";
            // line 46
            echo $this->getAttribute((isset($context["legend"]) ? $context["legend"] : null), "U_MOVE_UP");
            echo "\" data-ajax=\"row_up\">";
            echo (isset($context["ICON_MOVE_UP"]) ? $context["ICON_MOVE_UP"] : null);
            echo "</a></span>
\t\t\t\t<span class=\"down-disabled\" style=\"display:none;\">";
            // line 47
            echo (isset($context["ICON_MOVE_DOWN_DISABLED"]) ? $context["ICON_MOVE_DOWN_DISABLED"] : null);
            echo "</span>
\t\t\t\t<span class=\"down\"><a href=\"";
            // line 48
            echo $this->getAttribute((isset($context["legend"]) ? $context["legend"] : null), "U_MOVE_DOWN");
            echo "\" data-ajax=\"row_down\">";
            echo (isset($context["ICON_MOVE_DOWN"]) ? $context["ICON_MOVE_DOWN"] : null);
            echo "</a></span>
\t\t\t\t<a href=\"";
            // line 49
            echo $this->getAttribute((isset($context["legend"]) ? $context["legend"] : null), "U_DELETE");
            echo "\">";
            echo (isset($context["ICON_DELETE"]) ? $context["ICON_DELETE"] : null);
            echo "</a>
\t\t\t</td>
\t\t</tr>
\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 53
            echo "\t\t<tr>
\t\t\t<td colspan=\"3\" class=\"row3\">";
            // line 54
            echo $this->env->getExtension('phpbb')->lang("NO_GROUPS_ADDED");
            echo "</td>
\t\t</tr>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['legend'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 57
        echo "\t</tbody>
\t</table>

\t<form id=\"legend_add_group\" method=\"post\" action=\"";
        // line 60
        echo (isset($context["U_ACTION_LEGEND"]) ? $context["U_ACTION_LEGEND"] : null);
        echo "\">
\t\t<fieldset class=\"quick\">
\t\t\t<select name=\"g\">
\t\t\t\t<option value=\"0\">";
        // line 63
        echo $this->env->getExtension('phpbb')->lang("SELECT_GROUP");
        echo "</option>
\t\t\t\t";
        // line 64
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "add_legend"));
        foreach ($context['_seq'] as $context["_key"] => $context["add_legend"]) {
            // line 65
            echo "\t\t\t\t\t<option";
            if ($this->getAttribute((isset($context["add_legend"]) ? $context["add_legend"] : null), "GROUP_SPECIAL")) {
                echo " class=\"sep\"";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["add_legend"]) ? $context["add_legend"] : null), "GROUP_ID");
            echo "\">";
            echo $this->getAttribute((isset($context["add_legend"]) ? $context["add_legend"] : null), "GROUP_NAME");
            echo "</option>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['add_legend'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 67
        echo "\t\t\t</select>
\t\t\t<input class=\"button2\" type=\"submit\" name=\"submit\" value=\"";
        // line 68
        echo $this->env->getExtension('phpbb')->lang("ADD");
        echo "\" />
\t\t\t<input type=\"hidden\" name=\"action\" value=\"add\" />
\t\t\t";
        // line 70
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t\t</fieldset>
\t</form>

\t<h1>";
        // line 74
        echo $this->env->getExtension('phpbb')->lang("MANAGE_TEAMPAGE");
        echo "</h1>

\t<form id=\"teampage_settings\" method=\"post\" action=\"";
        // line 76
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\"";
        if ((isset($context["S_CAN_UPLOAD"]) ? $context["S_CAN_UPLOAD"] : null)) {
            echo " enctype=\"multipart/form-data\"";
        }
        echo ">

\t<fieldset>
\t\t<legend>";
        // line 79
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_SETTINGS");
        echo "</legend>
\t\t<dl>
\t\t\t<dt><label for=\"teampage_memberships\">";
        // line 81
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_MEMBERSHIPS");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t\t<dd>
\t\t\t\t<label><input type=\"radio\" name=\"teampage_memberships\" class=\"radio\" value=\"0\"";
        // line 83
        if (((isset($context["DISPLAY_MEMBERSHIPS"]) ? $context["DISPLAY_MEMBERSHIPS"] : null) == 0)) {
            echo " checked=\"checked\"";
        }
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_DISP_FIRST");
        echo "</label><br />
\t\t\t\t<label><input type=\"radio\" name=\"teampage_memberships\" class=\"radio\" value=\"1\"";
        // line 84
        if (((isset($context["DISPLAY_MEMBERSHIPS"]) ? $context["DISPLAY_MEMBERSHIPS"] : null) == 1)) {
            echo " checked=\"checked\"";
        }
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_DISP_DEFAULT");
        echo "</label><br />
\t\t\t\t<label><input type=\"radio\" name=\"teampage_memberships\" class=\"radio\" value=\"2\"";
        // line 85
        if (((isset($context["DISPLAY_MEMBERSHIPS"]) ? $context["DISPLAY_MEMBERSHIPS"] : null) == 2)) {
            echo " checked=\"checked\"";
        }
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_DISP_ALL");
        echo "</label>
\t\t\t</dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"teampage_forums\">";
        // line 89
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_FORUMS");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_FORUMS_EXPLAIN");
        echo "</span></dt>
\t\t\t<dd>
\t\t\t\t<label><input type=\"radio\" name=\"teampage_forums\" class=\"radio\" value=\"1\"";
        // line 91
        if ((isset($context["DISPLAY_FORUMS"]) ? $context["DISPLAY_FORUMS"] : null)) {
            echo " checked=\"checked\"";
        }
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("YES");
        echo "</label>
\t\t\t\t<label><input type=\"radio\" name=\"teampage_forums\" class=\"radio\" value=\"0\"";
        // line 92
        if ((!(isset($context["DISPLAY_FORUMS"]) ? $context["DISPLAY_FORUMS"] : null))) {
            echo " checked=\"checked\"";
        }
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("NO");
        echo "</label>
\t\t\t</dd>
\t\t</dl>

\t<p class=\"submit-buttons\">
\t\t<input class=\"button1\" type=\"submit\" name=\"update\" value=\"";
        // line 97
        echo $this->env->getExtension('phpbb')->lang("SUBMIT");
        echo "\" />&nbsp;
\t\t<input class=\"button2\" type=\"reset\" name=\"reset\" value=\"";
        // line 98
        echo $this->env->getExtension('phpbb')->lang("RESET");
        echo "\" />
\t\t<input type=\"hidden\" name=\"action\" value=\"set_config_teampage\" />
\t\t";
        // line 100
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t</p>
\t</fieldset>
\t</form>

\t<p>";
        // line 105
        echo $this->env->getExtension('phpbb')->lang("TEAMPAGE_EXPLAIN");
        echo "</p>

\t";
        // line 107
        if (((isset($context["S_TEAMPAGE_CATEGORY"]) ? $context["S_TEAMPAGE_CATEGORY"] : null) && (isset($context["CURRENT_CATEGORY_NAME"]) ? $context["CURRENT_CATEGORY_NAME"] : null))) {
            echo "<p><strong><a href=\"";
            echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("TEAMPAGE");
            echo "</a> &raquo; ";
            echo (isset($context["CURRENT_CATEGORY_NAME"]) ? $context["CURRENT_CATEGORY_NAME"] : null);
            echo "</strong></p>";
        }
        // line 108
        echo "
\t<table class=\"table1\">
\t\t<col class=\"col1\" /><col class=\"col2\" /><col class=\"col2\" />
\t<thead>
\t<tr>
\t\t<th style=\"width: 50%\">";
        // line 113
        echo $this->env->getExtension('phpbb')->lang("GROUP");
        echo "</th>
\t\t<th>";
        // line 114
        echo $this->env->getExtension('phpbb')->lang("GROUP_TYPE");
        echo "</th>
\t\t<th>";
        // line 115
        echo $this->env->getExtension('phpbb')->lang("ACTION");
        echo "</th>
\t</tr>
\t</thead>
\t<tbody>
\t";
        // line 119
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "teampage"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["teampage"]) {
            // line 120
            echo "\t\t<tr>
\t\t\t<td>
\t\t\t\t";
            // line 122
            if ($this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "U_CATEGORY")) {
                // line 123
                echo "\t\t\t\t\t<a href=\"";
                echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "U_CATEGORY");
                echo "\">";
                echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "GROUP_NAME");
                echo "</a>
\t\t\t\t";
            } else {
                // line 125
                echo "\t\t\t\t\t<strong";
                if ($this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "GROUP_COLOUR")) {
                    echo " style=\"color: ";
                    echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "GROUP_COLOUR");
                    echo "\"";
                }
                echo ">";
                echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "GROUP_NAME");
                echo "</strong>
\t\t\t\t";
            }
            // line 127
            echo "\t\t\t</td>
\t\t\t<td style=\"text-align: center;\">";
            // line 128
            if ($this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "GROUP_TYPE")) {
                echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "GROUP_TYPE");
            } else {
                echo "-";
            }
            // line 129
            echo "\t\t\t</td></td>
\t\t\t<td class=\"actions\">
\t\t\t\t<span class=\"up-disabled\" style=\"display: none;\">";
            // line 131
            echo (isset($context["ICON_MOVE_UP_DISABLED"]) ? $context["ICON_MOVE_UP_DISABLED"] : null);
            echo "</span>
\t\t\t\t<span class=\"up\"><a href=\"";
            // line 132
            echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "U_MOVE_UP");
            echo "\" data-ajax=\"row_up\">";
            echo (isset($context["ICON_MOVE_UP"]) ? $context["ICON_MOVE_UP"] : null);
            echo "</a></span>
\t\t\t\t<span class=\"down-disabled\" style=\"display:none;\">";
            // line 133
            echo (isset($context["ICON_MOVE_DOWN_DISABLED"]) ? $context["ICON_MOVE_DOWN_DISABLED"] : null);
            echo "</span>
\t\t\t\t<span class=\"down\"><a href=\"";
            // line 134
            echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "U_MOVE_DOWN");
            echo "\" data-ajax=\"row_down\">";
            echo (isset($context["ICON_MOVE_DOWN"]) ? $context["ICON_MOVE_DOWN"] : null);
            echo "</a></span>
\t\t\t\t<a href=\"";
            // line 135
            echo $this->getAttribute((isset($context["teampage"]) ? $context["teampage"] : null), "U_DELETE");
            echo "\">";
            echo (isset($context["ICON_DELETE"]) ? $context["ICON_DELETE"] : null);
            echo "</a>
\t\t\t</td>
\t\t</tr>
\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 139
            echo "\t\t<tr>
\t\t\t<td colspan=\"3\" class=\"row3\">";
            // line 140
            echo $this->env->getExtension('phpbb')->lang("NO_GROUPS_ADDED");
            echo "</td>
\t\t</tr>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['teampage'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 143
        echo "\t</tbody>
\t</table>

\t";
        // line 146
        if ((!(isset($context["S_TEAMPAGE_CATEGORY"]) ? $context["S_TEAMPAGE_CATEGORY"] : null))) {
            // line 147
            echo "\t<form id=\"teampage_add_category\" method=\"post\" action=\"";
            echo (isset($context["U_ACTION_TEAMPAGE"]) ? $context["U_ACTION_TEAMPAGE"] : null);
            echo "\">
\t\t<fieldset class=\"quick\">
\t\t\t<input class=\"inputbox autowidth\" type=\"text\" maxlength=\"255\" name=\"category_name\" placeholder=\"";
            // line 149
            echo $this->env->getExtension('phpbb')->lang("GROUP_CATEGORY_NAME");
            echo "\" />
\t\t\t<input class=\"button2\" type=\"submit\" name=\"submit\" value=\"";
            // line 150
            echo $this->env->getExtension('phpbb')->lang("ADD_GROUP_CATEGORY");
            echo "\" />
\t\t\t<input type=\"hidden\" name=\"action\" value=\"add_category\" />
\t\t\t";
            // line 152
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t\t</fieldset>
\t</form>
\t";
        }
        // line 156
        echo "
\t<form id=\"teampage_add_group\" method=\"post\" action=\"";
        // line 157
        echo (isset($context["U_ACTION_TEAMPAGE"]) ? $context["U_ACTION_TEAMPAGE"] : null);
        echo "\">
\t\t<fieldset class=\"quick\">
\t\t\t<select name=\"g\">
\t\t\t\t<option value=\"0\">";
        // line 160
        echo $this->env->getExtension('phpbb')->lang("SELECT_GROUP");
        echo "</option>
\t\t\t\t";
        // line 161
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "add_teampage"));
        foreach ($context['_seq'] as $context["_key"] => $context["add_teampage"]) {
            // line 162
            echo "\t\t\t\t\t<option";
            if ($this->getAttribute((isset($context["add_teampage"]) ? $context["add_teampage"] : null), "GROUP_SPECIAL")) {
                echo " class=\"sep\"";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["add_teampage"]) ? $context["add_teampage"] : null), "GROUP_ID");
            echo "\">";
            echo $this->getAttribute((isset($context["add_teampage"]) ? $context["add_teampage"] : null), "GROUP_NAME");
            echo "</option>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['add_teampage'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 164
        echo "\t\t\t</select>
\t\t\t<input class=\"button2\" type=\"submit\" name=\"submit\" value=\"";
        // line 165
        echo $this->env->getExtension('phpbb')->lang("ADD");
        echo "\" />
\t\t\t<input type=\"hidden\" name=\"action\" value=\"add\" />
\t\t\t";
        // line 167
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t\t</fieldset>
\t</form>

";
        // line 171
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("overall_footer.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "acp_groups_position.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  538 => 171,  531 => 167,  526 => 165,  523 => 164,  508 => 162,  504 => 161,  500 => 160,  494 => 157,  491 => 156,  484 => 152,  479 => 150,  475 => 149,  469 => 147,  467 => 146,  462 => 143,  453 => 140,  450 => 139,  439 => 135,  433 => 134,  429 => 133,  423 => 132,  419 => 131,  415 => 129,  409 => 128,  406 => 127,  394 => 125,  386 => 123,  384 => 122,  380 => 120,  375 => 119,  368 => 115,  364 => 114,  360 => 113,  353 => 108,  343 => 107,  338 => 105,  330 => 100,  325 => 98,  321 => 97,  309 => 92,  301 => 91,  293 => 89,  282 => 85,  274 => 84,  266 => 83,  260 => 81,  255 => 79,  245 => 76,  240 => 74,  233 => 70,  228 => 68,  225 => 67,  210 => 65,  206 => 64,  202 => 63,  196 => 60,  191 => 57,  182 => 54,  179 => 53,  168 => 49,  162 => 48,  158 => 47,  152 => 46,  148 => 45,  143 => 43,  133 => 42,  130 => 41,  125 => 40,  118 => 36,  114 => 35,  110 => 34,  101 => 28,  93 => 23,  88 => 21,  84 => 20,  72 => 15,  64 => 14,  56 => 12,  51 => 10,  41 => 7,  36 => 5,  31 => 2,  19 => 1,);
    }
}
