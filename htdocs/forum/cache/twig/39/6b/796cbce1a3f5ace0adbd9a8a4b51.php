<?php

/* ucp_notifications.html */
class __TwigTemplate_396b796cbce1a3f5ace0adbd9a8a4b51 extends Twig_Template
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
        $value = "notifications";
        $context['definition']->set('NAV_SECTION', $value);
        $location = "ucp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("ucp_header.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<form id=\"ucp\" method=\"post\" action=\"";
        // line 3
        echo (isset($context["S_UCP_ACTION"]) ? $context["S_UCP_ACTION"] : null);
        echo "\"";
        echo (isset($context["S_FORM_ENCTYPE"]) ? $context["S_FORM_ENCTYPE"] : null);
        echo ">

<h2>";
        // line 5
        echo (isset($context["TITLE"]) ? $context["TITLE"] : null);
        echo "</h2>
<div class=\"panel\">
\t<div class=\"inner\">

\t\t<p>";
        // line 9
        echo (isset($context["TITLE_EXPLAIN"]) ? $context["TITLE_EXPLAIN"] : null);
        echo "</p>

\t\t";
        // line 11
        if (((isset($context["MODE"]) ? $context["MODE"] : null) == "notification_options")) {
            // line 12
            echo "\t\t\t<table class=\"table1\">
\t\t\t\t<thead>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>";
            // line 15
            echo $this->env->getExtension('phpbb')->lang("NOTIFICATION_TYPE");
            echo "</th>
\t\t\t\t\t\t";
            // line 16
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_methods"));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                // line 17
                echo "\t\t\t\t\t\t\t<th class=\"mark\">";
                echo $this->getAttribute((isset($context["notification_methods"]) ? $context["notification_methods"] : null), "NAME");
                echo "</th>
\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 19
            echo "\t\t\t\t\t\t<th class=\"mark\">";
            echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
            echo "</th>
\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody>
\t\t\t\t";
            // line 23
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_types"));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_types"]) {
                // line 24
                echo "\t\t\t\t\t";
                if ($this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "GROUP_NAME")) {
                    // line 25
                    echo "\t\t\t\t\t\t<tr class=\"bg3\">
\t\t\t\t\t\t\t<td colspan=\"";
                    // line 26
                    echo (isset($context["NOTIFICATION_TYPES_COLS"]) ? $context["NOTIFICATION_TYPES_COLS"] : null);
                    echo "\">";
                    echo $this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "GROUP_NAME");
                    echo "</td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                } else {
                    // line 29
                    echo "\t\t\t\t\t\t<tr class=\"";
                    if (($this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "S_ROW_COUNT") % 2 == 1)) {
                        echo "bg1";
                    } else {
                        echo "bg2";
                    }
                    echo "\">
\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t";
                    // line 31
                    echo $this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "NAME");
                    echo "
\t\t\t\t\t\t\t\t";
                    // line 32
                    if ($this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "EXPLAIN")) {
                        echo "<br />&nbsp; &nbsp;";
                        echo $this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "EXPLAIN");
                    }
                    // line 33
                    echo "\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t";
                    // line 34
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "notification_methods"));
                    foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                        // line 35
                        echo "\t\t\t\t\t\t\t\t<td class=\"mark\"><input type=\"checkbox\" name=\"";
                        echo $this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "TYPE");
                        echo "_";
                        echo $this->getAttribute((isset($context["notification_methods"]) ? $context["notification_methods"] : null), "METHOD");
                        echo "\"";
                        if ($this->getAttribute((isset($context["notification_methods"]) ? $context["notification_methods"] : null), "SUBSCRIBED")) {
                            echo " checked=\"checked\"";
                        }
                        echo " /></td>
\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 37
                    echo "\t\t\t\t\t\t\t<td class=\"mark\"><input type=\"checkbox\" name=\"";
                    echo $this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "TYPE");
                    echo "_notification\"";
                    if ($this->getAttribute((isset($context["notification_types"]) ? $context["notification_types"] : null), "SUBSCRIBED")) {
                        echo " checked=\"checked\"";
                    }
                    echo " /></td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                }
                // line 40
                echo "\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_types'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 41
            echo "\t\t\t\t</tbody>
\t\t\t</table>
\t\t";
        } else {
            // line 44
            echo "\t\t\t";
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list"))) {
                // line 45
                echo "\t\t\t\t<div class=\"action-bar top\">
\t\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t\t";
                // line 47
                if ((isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null)) {
                    echo "<a href=\"";
                    echo (isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null);
                    echo "\" class=\"mark\">";
                    echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS_MARK_ALL_READ");
                    echo "</a> &bull; ";
                }
                // line 48
                echo "\t\t\t\t\t\t";
                echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo (isset($context["TOTAL_COUNT"]) ? $context["TOTAL_COUNT"] : null);
                echo "</strong>]
\t\t\t\t\t\t";
                // line 49
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                    // line 50
                    echo "\t\t\t\t\t\t\t";
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
                    // line 51
                    echo "\t\t\t\t\t\t";
                } else {
                    // line 52
                    echo "\t\t\t\t\t\t\t &bull; ";
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t\t\t\t";
                }
                // line 54
                echo "\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t<div class=\"notification_list\">
\t\t\t\t<ul class=\"topiclist two-columns\">
\t\t\t\t\t<li class=\"header\">
\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 61
                echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
                echo "</div></dt>
\t\t\t\t\t\t\t<dd class=\"mark\">";
                // line 62
                echo $this->env->getExtension('phpbb')->lang("MARK_READ");
                echo "</dd>
\t\t\t\t\t\t</dl>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t\t<ul class=\"topiclist cplist two-columns\">
\t\t\t\t\t";
                // line 67
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list"));
                foreach ($context['_seq'] as $context["_key"] => $context["notification_list"]) {
                    // line 68
                    echo "\t\t\t\t\t\t<li class=\"row";
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "UNREAD")) {
                        echo " bg3";
                    } else {
                        if (($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "S_ROW_COUNT") % 2 == 1)) {
                            echo " bg1";
                        } else {
                            echo " bg2";
                        }
                    }
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "STYLING")) {
                        echo " ";
                        echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "STYLING");
                    }
                    echo "\">
\t\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t\t<dt>
\t\t\t\t\t\t\t\t\t<div class=\"list-inner\">\t\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t\t";
                    // line 72
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "AVATAR")) {
                        echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "AVATAR");
                    } else {
                        echo "<img src=\"";
                        echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
                        echo "/images/no_avatar.png\" alt=\"\" />";
                    }
                    // line 73
                    echo "\t\t\t\t\t\t\t\t\t\t<div class=\"notifications\">
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 74
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "URL")) {
                        echo "<a href=\"";
                        if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "UNREAD")) {
                            echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "U_MARK_READ");
                        } else {
                            echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "URL");
                        }
                        echo "\">";
                    }
                    // line 75
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_title\">";
                    echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "FORMATTED_TITLE");
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "REFERENCE")) {
                        echo " ";
                        echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "REFERENCE");
                    }
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 76
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "URL")) {
                        echo "</a>";
                    }
                    echo "\t\t\t\t
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 77
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "FORUM")) {
                        echo "<p class=\"notifications_forum\">";
                        echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "FORUM");
                        echo "</p>";
                    }
                    // line 78
                    echo "\t\t\t\t\t\t\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "REASON")) {
                        echo "<p class=\"notifications_reason\">";
                        echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "REASON");
                        echo "</p>";
                    }
                    // line 79
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_time\">";
                    echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "TIME");
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</dt>

\t\t\t\t\t\t\t\t<dd class=\"mark\">&nbsp;<input type=\"checkbox\" name=\"mark[]\" value=\"";
                    // line 84
                    echo $this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "NOTIFICATION_ID");
                    echo "\"";
                    if ((!$this->getAttribute((isset($context["notification_list"]) ? $context["notification_list"] : null), "UNREAD"))) {
                        echo " disabled=\"disabled\"";
                    }
                    echo " /> <dfn>";
                    echo $this->env->getExtension('phpbb')->lang("MARK_READ");
                    echo "</dfn>&nbsp;</dd>
\t\t\t\t\t\t\t</dl>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_list'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 88
                echo "\t\t\t\t</ul>
\t\t\t</div>

\t\t\t<div class=\"action-bar bottom\">
\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t";
                // line 93
                echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo (isset($context["TOTAL_COUNT"]) ? $context["TOTAL_COUNT"] : null);
                echo "</strong>]
\t\t\t\t\t";
                // line 94
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                    // line 95
                    echo "\t\t\t\t\t\t";
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
                    // line 96
                    echo "\t\t\t\t\t";
                } else {
                    // line 97
                    echo "\t\t\t\t\t\t  &bull; ";
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t\t\t";
                }
                // line 99
                echo "\t\t\t\t</div>
\t\t\t</div>

\t\t\t";
            } else {
                // line 103
                echo "\t\t\t\t<p><strong>";
                echo $this->env->getExtension('phpbb')->lang("NO_NOTIFICATIONS");
                echo "</strong></p>
\t\t\t";
            }
            // line 105
            echo "
\t\t";
        }
        // line 107
        echo "\t</div>
</div>

";
        // line 110
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_types")) || twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list")))) {
            // line 111
            echo "<fieldset class=\"display-actions\">
\t<input type=\"hidden\" name=\"form_time\" value=\"";
            // line 112
            echo (isset($context["FORM_TIME"]) ? $context["FORM_TIME"] : null);
            echo "\" />
\t";
            // line 113
            echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
            echo "
\t<input type=\"submit\" name=\"submit\" value=\"";
            // line 114
            if (((isset($context["MODE"]) ? $context["MODE"] : null) == "notification_options")) {
                echo $this->env->getExtension('phpbb')->lang("SUBMIT");
            } else {
                echo $this->env->getExtension('phpbb')->lang("MARK_READ");
            }
            echo "\" class=\"button1\" />
\t<div><a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', true); return false;\">";
            // line 115
            echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', false); return false;\">";
            echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
            echo "</a></div>
\t";
            // line 116
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
</fieldset>
";
        }
        // line 119
        echo "
</form>

";
        // line 122
        $location = "ucp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("ucp_footer.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ucp_notifications.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  429 => 122,  424 => 119,  418 => 116,  412 => 115,  404 => 114,  400 => 113,  396 => 112,  393 => 111,  391 => 110,  386 => 107,  382 => 105,  376 => 103,  370 => 99,  364 => 97,  361 => 96,  348 => 95,  346 => 94,  340 => 93,  333 => 88,  317 => 84,  308 => 79,  301 => 78,  295 => 77,  289 => 76,  280 => 75,  270 => 74,  267 => 73,  259 => 72,  239 => 68,  235 => 67,  227 => 62,  223 => 61,  214 => 54,  208 => 52,  205 => 51,  192 => 50,  190 => 49,  183 => 48,  175 => 47,  171 => 45,  168 => 44,  163 => 41,  157 => 40,  146 => 37,  131 => 35,  127 => 34,  124 => 33,  119 => 32,  115 => 31,  105 => 29,  97 => 26,  94 => 25,  91 => 24,  87 => 23,  79 => 19,  70 => 17,  66 => 16,  62 => 15,  57 => 12,  55 => 11,  50 => 9,  43 => 5,  36 => 3,  33 => 2,  19 => 1,);
    }
}
