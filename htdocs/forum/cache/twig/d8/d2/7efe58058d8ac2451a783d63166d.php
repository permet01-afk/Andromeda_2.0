<?php

/* ucp_pm_options.html */
class __TwigTemplate_d8d27efe58058d8ac2451a783d63166d extends Twig_Template
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
<h2>";
        // line 3
        echo $this->env->getExtension('phpbb')->lang("TITLE");
        echo "</h2>

<form id=\"ucp\" method=\"post\" action=\"";
        // line 5
        echo (isset($context["S_UCP_ACTION"]) ? $context["S_UCP_ACTION"] : null);
        echo "\">

<div class=\"panel\">
\t<div class=\"inner\">

\t";
        // line 10
        if ((isset($context["ERROR_MESSAGE"]) ? $context["ERROR_MESSAGE"] : null)) {
            echo "<p class=\"error\">";
            echo (isset($context["ERROR_MESSAGE"]) ? $context["ERROR_MESSAGE"] : null);
            echo "</p>";
        }
        // line 11
        echo "\t";
        if ((isset($context["NOTIFICATION_MESSAGE"]) ? $context["NOTIFICATION_MESSAGE"] : null)) {
            echo "<p class=\"error\">";
            echo (isset($context["NOTIFICATION_MESSAGE"]) ? $context["NOTIFICATION_MESSAGE"] : null);
            echo "</p>";
        }
        // line 12
        echo "
\t<h3>";
        // line 13
        echo $this->env->getExtension('phpbb')->lang("DEFINED_RULES");
        echo "</h3>

\t<ol class=\"def-rules\">
\t";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "rule"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["rule"]) {
            // line 17
            echo "\t\t<li><div class=\"right-box\"><input type=\"submit\" name=\"delete_rule[";
            echo $this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "RULE_ID");
            echo "]\" value=\"";
            echo $this->env->getExtension('phpbb')->lang("DELETE_RULE");
            echo "\" class=\"button2\" /></div><strong>";
            echo $this->env->getExtension('phpbb')->lang("IF");
            echo "</strong> ";
            echo $this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "CHECK");
            echo " <em>";
            echo $this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "RULE");
            echo "</em> ";
            if ($this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "STRING")) {
                echo "<strong>";
                echo $this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "STRING");
                echo "</strong> | ";
            }
            echo $this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "ACTION");
            if ($this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "FOLDER")) {
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo " ";
                echo $this->getAttribute((isset($context["rule"]) ? $context["rule"] : null), "FOLDER");
            }
            echo "<div style=\"clear: both;\"></div></li>
\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 19
            echo "\t\t<li><strong>";
            echo $this->env->getExtension('phpbb')->lang("NO_RULES_DEFINED");
            echo "</strong></li>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rule'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 21
        echo "\t</ol>

\t<h3>";
        // line 23
        echo $this->env->getExtension('phpbb')->lang("ADD_NEW_RULE");
        echo "</h3>

\t<fieldset class=\"fields2\">

\t";
        // line 27
        if ((isset($context["S_CHECK_DEFINED"]) ? $context["S_CHECK_DEFINED"] : null)) {
            // line 28
            echo "\t\t<dl>
\t\t\t<dt><label";
            // line 29
            if ((isset($context["S_CHECK_SELECT"]) ? $context["S_CHECK_SELECT"] : null)) {
                echo " for=\"check_option\"";
            }
            echo ">";
            echo $this->env->getExtension('phpbb')->lang("IF");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t\t<dd>
\t\t\t\t";
            // line 31
            if ((isset($context["S_CHECK_SELECT"]) ? $context["S_CHECK_SELECT"] : null)) {
                echo "<select name=\"check_option\" id=\"check_option\">";
                echo (isset($context["S_CHECK_OPTIONS"]) ? $context["S_CHECK_OPTIONS"] : null);
                echo "</select> <input type=\"submit\" name=\"next\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("NEXT_STEP");
                echo "\" class=\"button2\" />";
            } else {
                echo (isset($context["CHECK_CURRENT"]) ? $context["CHECK_CURRENT"] : null);
                echo "<input type=\"hidden\" name=\"check_option\" value=\"";
                echo (isset($context["CHECK_OPTION"]) ? $context["CHECK_OPTION"] : null);
                echo "\" />";
            }
            // line 32
            echo "\t\t\t</dd>
\t\t</dl>
\t";
        }
        // line 35
        echo "
\t";
        // line 36
        if ((isset($context["S_RULE_DEFINED"]) ? $context["S_RULE_DEFINED"] : null)) {
            // line 37
            echo "\t\t<dl>
\t\t\t<dt>";
            // line 38
            if ((isset($context["S_RULE_SELECT"]) ? $context["S_RULE_SELECT"] : null)) {
                echo "<input type=\"submit\" name=\"back[rule]\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("PREVIOUS_STEP");
                echo "\" class=\"button2\" />";
            } else {
                echo "<label>&nbsp;</label>";
            }
            echo "</dt>
\t\t\t<dd>";
            // line 39
            if ((isset($context["S_RULE_SELECT"]) ? $context["S_RULE_SELECT"] : null)) {
                echo "<select name=\"rule_option\" id=\"rule_option\">";
                echo (isset($context["S_RULE_OPTIONS"]) ? $context["S_RULE_OPTIONS"] : null);
                echo "</select> <input type=\"submit\" name=\"next\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("NEXT_STEP");
                echo "\" class=\"button2\" />";
            } else {
                echo "<em>";
                echo (isset($context["RULE_CURRENT"]) ? $context["RULE_CURRENT"] : null);
                echo "</em><input type=\"hidden\" name=\"rule_option\" value=\"";
                echo (isset($context["RULE_OPTION"]) ? $context["RULE_OPTION"] : null);
                echo "\" />";
            }
            echo "</dd>
\t\t</dl>
\t";
        }
        // line 42
        echo "
\t";
        // line 43
        if ((isset($context["S_COND_DEFINED"]) ? $context["S_COND_DEFINED"] : null)) {
            // line 44
            echo "\t\t";
            if (((isset($context["S_COND_SELECT"]) ? $context["S_COND_SELECT"] : null) || (isset($context["COND_CURRENT"]) ? $context["COND_CURRENT"] : null))) {
                // line 45
                echo "\t\t\t<dl>
\t\t\t\t<dt>";
                // line 46
                if ((isset($context["S_COND_SELECT"]) ? $context["S_COND_SELECT"] : null)) {
                    echo "<input type=\"submit\" name=\"back[cond]\" value=\"";
                    echo $this->env->getExtension('phpbb')->lang("PREVIOUS_STEP");
                    echo "\" class=\"button2\" />";
                } else {
                    echo "<label>&nbsp;</label>";
                }
                echo "</dt>
\t\t\t\t<dd>
\t\t\t\t\t";
                // line 48
                if ((isset($context["S_COND_SELECT"]) ? $context["S_COND_SELECT"] : null)) {
                    // line 49
                    echo "\t\t\t\t\t\t";
                    if ((isset($context["S_TEXT_CONDITION"]) ? $context["S_TEXT_CONDITION"] : null)) {
                        // line 50
                        echo "\t\t\t\t\t\t\t<input type=\"text\" name=\"rule_string\" value=\"";
                        echo (isset($context["CURRENT_STRING"]) ? $context["CURRENT_STRING"] : null);
                        echo "\" class=\"inputbox medium\" maxlength=\"250\" />
\t\t\t\t\t\t";
                    } elseif ((isset($context["S_USER_CONDITION"]) ? $context["S_USER_CONDITION"] : null)) {
                        // line 52
                        echo "\t\t\t\t\t\t\t<input type=\"text\" name=\"rule_string\" value=\"";
                        echo (isset($context["CURRENT_STRING"]) ? $context["CURRENT_STRING"] : null);
                        echo "\" class=\"inputbox tiny\" />&nbsp;<span>[ <a href=\"";
                        echo (isset($context["U_FIND_USERNAME"]) ? $context["U_FIND_USERNAME"] : null);
                        echo "\" onclick=\"find_username(this.href); return false;\">";
                        echo $this->env->getExtension('phpbb')->lang("FIND_USERNAME");
                        echo "</a> ]</span>
\t\t\t\t\t\t";
                    } elseif ((isset($context["S_GROUP_CONDITION"]) ? $context["S_GROUP_CONDITION"] : null)) {
                        // line 54
                        echo "\t\t\t\t\t\t\t<input type=\"hidden\" name=\"rule_string\" value=\"";
                        echo (isset($context["CURRENT_STRING"]) ? $context["CURRENT_STRING"] : null);
                        echo "\" />";
                        if ((isset($context["S_GROUP_OPTIONS"]) ? $context["S_GROUP_OPTIONS"] : null)) {
                            echo "<select name=\"rule_group_id\">";
                            echo (isset($context["S_GROUP_OPTIONS"]) ? $context["S_GROUP_OPTIONS"] : null);
                            echo "</select>";
                        } else {
                            echo $this->env->getExtension('phpbb')->lang("NO_GROUPS");
                        }
                        // line 55
                        echo "\t\t\t\t\t\t";
                    }
                    // line 56
                    echo "\t\t\t\t\t\t<input type=\"submit\" name=\"next\" value=\"";
                    echo $this->env->getExtension('phpbb')->lang("NEXT_STEP");
                    echo "\" class=\"button2\" />
\t\t\t\t\t";
                } else {
                    // line 58
                    echo "\t\t\t\t\t\t<strong>";
                    echo (isset($context["COND_CURRENT"]) ? $context["COND_CURRENT"] : null);
                    echo "</strong><input type=\"hidden\" name=\"rule_string\" value=\"";
                    echo (isset($context["CURRENT_STRING"]) ? $context["CURRENT_STRING"] : null);
                    echo "\" /><input type=\"hidden\" name=\"rule_user_id\" value=\"";
                    echo (isset($context["CURRENT_USER_ID"]) ? $context["CURRENT_USER_ID"] : null);
                    echo "\" /><input type=\"hidden\" name=\"rule_group_id\" value=\"";
                    echo (isset($context["CURRENT_GROUP_ID"]) ? $context["CURRENT_GROUP_ID"] : null);
                    echo "\" />
\t\t\t\t\t";
                }
                // line 60
                echo "\t\t\t\t</dd>
\t\t\t</dl>
\t\t";
            }
            // line 63
            echo "\t\t<input type=\"hidden\" name=\"cond_option\" value=\"";
            echo (isset($context["COND_OPTION"]) ? $context["COND_OPTION"] : null);
            echo "\" />
\t";
        }
        // line 65
        echo "

\t";
        // line 67
        if ((isset($context["NONE_CONDITION"]) ? $context["NONE_CONDITION"] : null)) {
            echo "<input type=\"hidden\" name=\"cond_option\" value=\"none\" />";
        }
        // line 68
        echo "
\t";
        // line 69
        if ((isset($context["S_ACTION_DEFINED"]) ? $context["S_ACTION_DEFINED"] : null)) {
            // line 70
            echo "\t\t<dl>
\t\t\t<dt>";
            // line 71
            if ((isset($context["S_ACTION_SELECT"]) ? $context["S_ACTION_SELECT"] : null)) {
                echo "<input type=\"submit\" name=\"back[action]\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("PREVIOUS_STEP");
                echo "\" class=\"button2\" />";
            } else {
                echo "<label>&nbsp;</label>";
            }
            echo "</dt>
\t\t\t<dd>";
            // line 72
            if ((isset($context["S_ACTION_SELECT"]) ? $context["S_ACTION_SELECT"] : null)) {
                echo " <select name=\"action_option\">";
                echo (isset($context["S_ACTION_OPTIONS"]) ? $context["S_ACTION_OPTIONS"] : null);
                echo "</select> <input type=\"submit\" name=\"add_rule\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("ADD_RULE");
                echo "\" class=\"button1\" />";
            } else {
                echo (isset($context["ACTION_CURRENT"]) ? $context["ACTION_CURRENT"] : null);
                echo "<input type=\"hidden\" name=\"action_option\" value=\"";
                echo (isset($context["ACTION_OPTION"]) ? $context["ACTION_OPTION"] : null);
                echo "\" />";
            }
            echo "</dd>
\t\t</dl>
\t";
        }
        // line 75
        echo "
\t</fieldset>

\t<h3>";
        // line 78
        echo $this->env->getExtension('phpbb')->lang("FOLDER_OPTIONS");
        echo "</h3>

\t<fieldset class=\"fields2\">

\t";
        // line 82
        if ((!(isset($context["S_MAX_FOLDER_ZERO"]) ? $context["S_MAX_FOLDER_ZERO"] : null))) {
            // line 83
            echo "\t<dl>
\t\t<dt><label for=\"foldername\">";
            // line 84
            echo $this->env->getExtension('phpbb')->lang("ADD_FOLDER");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd>";
            // line 85
            if ((isset($context["S_MAX_FOLDER_REACHED"]) ? $context["S_MAX_FOLDER_REACHED"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("MAX_FOLDER_REACHED");
            } else {
                echo "<input type=\"text\" class=\"inputbox medium\" name=\"foldername\" id=\"foldername\" size=\"30\" maxlength=\"30\" /> <input class=\"button2\" type=\"submit\" name=\"addfolder\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("ADD");
                echo "\" />";
            }
            echo "</dd>
\t</dl>
\t";
            // line 87
            if ((isset($context["S_FOLDER_OPTIONS"]) ? $context["S_FOLDER_OPTIONS"] : null)) {
                echo "<hr class=\"dashed\" />";
            }
            // line 88
            echo "\t";
        }
        // line 89
        echo "


\t";
        // line 92
        if ((isset($context["S_FOLDER_OPTIONS"]) ? $context["S_FOLDER_OPTIONS"] : null)) {
            // line 93
            echo "\t\t<dl>
\t\t\t<dt><label for=\"rename_folder_id\">";
            // line 94
            echo $this->env->getExtension('phpbb')->lang("RENAME_FOLDER");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t\t<dd><select name=\"rename_folder_id\" id=\"rename_folder_id\">";
            // line 95
            echo (isset($context["S_FOLDER_OPTIONS"]) ? $context["S_FOLDER_OPTIONS"] : null);
            echo "</select></dd>
\t\t\t<dt><label for=\"new_folder_name\">";
            // line 96
            echo $this->env->getExtension('phpbb')->lang("NEW_FOLDER_NAME");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t\t<dd><input type=\"text\" class=\"inputbox tiny\" name=\"new_folder_name\" id=\"new_folder_name\" maxlength=\"30\" /> <input class=\"button2\" type=\"submit\" name=\"rename_folder\" value=\"";
            // line 97
            echo $this->env->getExtension('phpbb')->lang("RENAME");
            echo "\" /></dd>
\t\t</dl>
\t\t<hr class=\"dashed\" />
\t\t<dl>
\t\t\t<dt><label for=\"remove_folder_id\">";
            // line 101
            echo $this->env->getExtension('phpbb')->lang("REMOVE_FOLDER");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t\t<dd><select name=\"remove_folder_id\" id=\"remove_folder_id\">";
            // line 102
            echo (isset($context["S_FOLDER_OPTIONS"]) ? $context["S_FOLDER_OPTIONS"] : null);
            echo "</select></dd>
\t\t\t<dd style=\"margin-top: 3px;\"><label for=\"remove_action1\"><input type=\"radio\" name=\"remove_action\" id=\"remove_action1\" value=\"1\" checked=\"checked\" /> ";
            // line 103
            echo $this->env->getExtension('phpbb')->lang("MOVE_DELETED_MESSAGES_TO");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label> <select name=\"move_to\">";
            echo (isset($context["S_TO_FOLDER_OPTIONS"]) ? $context["S_TO_FOLDER_OPTIONS"] : null);
            echo "</select></dd>
\t\t\t<dd style=\"margin-top: 3px;\"><label for=\"remove_action2\"><input type=\"radio\" name=\"remove_action\" id=\"remove_action2\" value=\"2\" /> ";
            // line 104
            echo $this->env->getExtension('phpbb')->lang("DELETE_MESSAGES_IN_FOLDER");
            echo "</label></dd>
\t\t\t<dd style=\"margin-top: 3px;\"><input class=\"button2\" type=\"submit\" name=\"remove_folder\" value=\"";
            // line 105
            echo $this->env->getExtension('phpbb')->lang("REMOVE");
            echo "\" /></dd>
\t\t</dl>
\t";
        }
        // line 108
        echo "
\t<hr class=\"dashed\" />

\t<dl>
\t\t<dt><label for=\"full_action1\">";
        // line 112
        echo $this->env->getExtension('phpbb')->lang("IF_FOLDER_FULL");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t<dd style=\"margin-top: 3px;\"><label for=\"full_action1\"><input type=\"radio\" name=\"full_action\" id=\"full_action1\" value=\"1\"";
        // line 113
        echo (isset($context["S_DELETE_CHECKED"]) ? $context["S_DELETE_CHECKED"] : null);
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("DELETE_OLDEST_MESSAGES");
        echo "</label></dd>
\t\t<dd style=\"margin-top: 3px;\"><label for=\"full_action2\"><input type=\"radio\" name=\"full_action\" id=\"full_action2\" value=\"2\"";
        // line 114
        echo (isset($context["S_MOVE_CHECKED"]) ? $context["S_MOVE_CHECKED"] : null);
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("MOVE_TO_FOLDER");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label> <select name=\"full_move_to\">";
        echo (isset($context["S_FULL_FOLDER_OPTIONS"]) ? $context["S_FULL_FOLDER_OPTIONS"] : null);
        echo "</select></dd>
\t\t<dd style=\"margin-top: 3px;\"><label for=\"full_action3\" style=\"white-space:normal;\"><input type=\"radio\" name=\"full_action\" id=\"full_action3\" value=\"3\"";
        // line 115
        echo (isset($context["S_HOLD_CHECKED"]) ? $context["S_HOLD_CHECKED"] : null);
        echo " /> ";
        echo $this->env->getExtension('phpbb')->lang("HOLD_NEW_MESSAGES");
        echo "</label></dd>
\t</dl>


\t<dl>
\t\t<dt><label>";
        // line 120
        echo $this->env->getExtension('phpbb')->lang("DEFAULT_ACTION");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("DEFAULT_ACTION_EXPLAIN");
        echo "</span></dt>
\t\t<dd>";
        // line 121
        echo (isset($context["DEFAULT_ACTION"]) ? $context["DEFAULT_ACTION"] : null);
        echo "</dd>
\t\t<dd><input class=\"button2\" type=\"submit\" name=\"fullfolder\" value=\"";
        // line 122
        echo $this->env->getExtension('phpbb')->lang("CHANGE");
        echo "\" /></dd>
\t</dl>
\t</fieldset>

\t</div>
\t";
        // line 127
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
</div>
</form>

";
        // line 131
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
        return "ucp_pm_options.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  479 => 131,  472 => 127,  464 => 122,  460 => 121,  453 => 120,  443 => 115,  434 => 114,  428 => 113,  423 => 112,  417 => 108,  411 => 105,  407 => 104,  400 => 103,  396 => 102,  391 => 101,  384 => 97,  379 => 96,  375 => 95,  370 => 94,  367 => 93,  365 => 92,  360 => 89,  357 => 88,  353 => 87,  342 => 85,  337 => 84,  334 => 83,  332 => 82,  325 => 78,  320 => 75,  303 => 72,  293 => 71,  290 => 70,  288 => 69,  285 => 68,  281 => 67,  277 => 65,  271 => 63,  266 => 60,  254 => 58,  248 => 56,  245 => 55,  234 => 54,  224 => 52,  218 => 50,  215 => 49,  213 => 48,  202 => 46,  199 => 45,  196 => 44,  194 => 43,  191 => 42,  173 => 39,  163 => 38,  160 => 37,  158 => 36,  155 => 35,  150 => 32,  137 => 31,  127 => 29,  124 => 28,  122 => 27,  115 => 23,  111 => 21,  102 => 19,  74 => 17,  69 => 16,  63 => 13,  60 => 12,  53 => 11,  47 => 10,  39 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }
}
