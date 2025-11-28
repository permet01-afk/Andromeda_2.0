<?php

/* ucp_pm_viewfolder.html */
class __TwigTemplate_cb6d5413d584dc0bc5b72919653d72b9 extends Twig_Template
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
        ob_start();
        // line 2
        echo "\t";
        if ((isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null)) {
            // line 3
            echo "\t\t<li class=\"small-icon icon-mark\"><a href=\"";
            echo (isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null);
            echo "\" class=\"mark\">";
            echo $this->env->getExtension('phpbb')->lang("PM_MARK_ALL_READ");
            echo "</a></li>
\t";
        }
        // line 5
        $value = 1;
        $context['definition']->set('NAVLINKS_SHOW_DEFAULT', $value);
        $value = ('' === $value = ob_get_clean()) ? '' : new \Twig_Markup($value, $this->env->getCharset());
        $context['definition']->set('NAVLINKS', $value);
        // line 7
        $value = "pm";
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
        // line 8
        echo "
";
        // line 9
        if ((!(isset($context["PROMPT"]) ? $context["PROMPT"] : null))) {
            // line 10
            echo "\t";
            $location = "ucp_pm_message_header.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->env->loadTemplate("ucp_pm_message_header.html")->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
        // line 12
        echo "
";
        // line 13
        if ((isset($context["PROMPT"]) ? $context["PROMPT"] : null)) {
            // line 14
            echo "\t<h2>";
            echo $this->env->getExtension('phpbb')->lang("EXPORT_AS_CSV");
            echo "</h2>
\t<form id=\"viewfolder\" method=\"post\" action=\"";
            // line 15
            echo (isset($context["S_PM_ACTION"]) ? $context["S_PM_ACTION"] : null);
            echo "\">
\t<div class=\"panel\">
\t\t<div class=\"inner\">
\t\t<h3>";
            // line 18
            echo $this->env->getExtension('phpbb')->lang("OPTIONS");
            echo "</h3>
\t\t<fieldset>
\t\t\t<dl>
\t\t\t\t<dt><label for=\"delimiter\">";
            // line 21
            echo $this->env->getExtension('phpbb')->lang("DELIMITER");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t\t\t<dd><input class=\"inputbox\" type=\"text\" id=\"delimiter\" name=\"delimiter\" value=\",\" /></dd>
\t\t\t</dl>
\t\t\t<dl>
\t\t\t\t<dt><label for=\"enclosure\">";
            // line 25
            echo $this->env->getExtension('phpbb')->lang("ENCLOSURE");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t\t\t<dd><input class=\"inputbox\" type=\"text\" id=\"enclosure\" name=\"enclosure\" value=\"&#034;\" /></dd>
\t\t\t</dl>
\t\t</fieldset>
\t\t</div>
\t</div>
\t<fieldset class=\"submit-buttons\">
\t\t<input type=\"hidden\" name=\"export_option\" value=\"CSV\" />
\t\t<input class=\"button1\" type=\"submit\" name=\"submit_export\" value=\"";
            // line 33
            echo $this->env->getExtension('phpbb')->lang("EXPORT_FOLDER");
            echo "\" />&nbsp;
\t\t<input class=\"button2\" type=\"reset\" value=\"Reset\" name=\"reset\" />&nbsp;
\t\t";
            // line 35
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t</fieldset>
\t</form>

";
        } else {
            // line 40
            echo "
\t";
            // line 41
            if ((isset($context["NUM_REMOVED"]) ? $context["NUM_REMOVED"] : null)) {
                // line 42
                echo "\t\t<div class=\"notice\">
\t\t\t<p>";
                // line 43
                echo (isset($context["RULE_REMOVED_MESSAGES"]) ? $context["RULE_REMOVED_MESSAGES"] : null);
                echo "</p>
\t\t</div>
\t";
            }
            // line 46
            echo "
\t";
            // line 47
            if ((isset($context["NUM_NOT_MOVED"]) ? $context["NUM_NOT_MOVED"] : null)) {
                // line 48
                echo "\t\t<div class=\"notice\">
\t\t\t<p>";
                // line 49
                echo (isset($context["NOT_MOVED_MESSAGES"]) ? $context["NOT_MOVED_MESSAGES"] : null);
                echo "<br />";
                echo (isset($context["RELEASE_MESSAGE_INFO"]) ? $context["RELEASE_MESSAGE_INFO"] : null);
                echo "</p>
\t\t</div>
\t";
            }
            // line 52
            echo "
\t";
            // line 53
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "messagerow"))) {
                // line 54
                echo "\t\t<ul class=\"topiclist two-columns\">
\t\t\t<li class=\"header\">
\t\t\t\t<dl>
\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 57
                echo $this->env->getExtension('phpbb')->lang("MESSAGE");
                echo "</div></dt>
\t\t\t\t\t<dd class=\"mark\">";
                // line 58
                echo $this->env->getExtension('phpbb')->lang("MARK");
                echo "</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t</ul>
\t\t<ul class=\"topiclist cplist pmlist responsive-show-all ";
                // line 62
                if ((isset($context["S_SHOW_RECIPIENTS"]) ? $context["S_SHOW_RECIPIENTS"] : null)) {
                    echo "missing-column";
                } else {
                    echo "two-columns";
                }
                echo "\">

\t\t";
                // line 64
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "messagerow"));
                foreach ($context['_seq'] as $context["_key"] => $context["messagerow"]) {
                    // line 65
                    echo "\t\t\t<li class=\"row";
                    if (($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "S_ROW_COUNT") % 2 == 1)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    if ($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "PM_CLASS")) {
                        echo " ";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "PM_CLASS");
                    }
                    echo "\">
\t\t\t\t<dl class=\"icon ";
                    // line 66
                    echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "FOLDER_IMG_STYLE");
                    echo "\">
\t\t\t\t\t<dt";
                    // line 67
                    if (($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "PM_ICON_URL") && (isset($context["S_PM_ICONS"]) ? $context["S_PM_ICONS"] : null))) {
                        echo " style=\"background-image: url(";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "PM_ICON_URL");
                        echo "); background-repeat: no-repeat;\"";
                    }
                    echo ">
\t\t\t\t\t\t";
                    // line 68
                    if (($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "S_PM_UNREAD") && (!$this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "S_PM_DELETED")))) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "U_VIEW_PM");
                        echo "\" class=\"icon-link\"></a>";
                    }
                    // line 69
                    echo "\t\t\t\t\t\t<div class=\"list-inner\">

\t\t\t\t\t\t";
                    // line 71
                    if ($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "S_PM_DELETED")) {
                        // line 72
                        echo "\t\t\t\t\t\t\t<a href=\"";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "U_REMOVE_PM");
                        echo "\" class=\"topictitle\">";
                        echo $this->env->getExtension('phpbb')->lang("DELETE_MESSAGE");
                        echo "</a><br />
\t\t\t\t\t\t\t<span class=\"error\">";
                        // line 73
                        echo $this->env->getExtension('phpbb')->lang("MESSAGE_REMOVED_FROM_OUTBOX");
                        echo "</span>
\t\t\t\t\t\t";
                    } else {
                        // line 75
                        echo "\t\t\t\t\t\t\t<a href=\"";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "U_VIEW_PM");
                        echo "\" class=\"topictitle\">";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "SUBJECT");
                        echo "</a>
\t\t\t\t\t\t";
                    }
                    // line 77
                    echo "\t\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "S_AUTHOR_DELETED")) {
                        // line 78
                        echo "\t\t\t\t\t\t\t<br /><em class=\"small\">";
                        echo $this->env->getExtension('phpbb')->lang("PM_FROM_REMOVED_AUTHOR");
                        echo "</em>
\t\t\t\t\t\t";
                    }
                    // line 80
                    echo "
\t\t\t\t\t\t";
                    // line 81
                    if ($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "S_PM_REPORTED")) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "U_MCP_REPORT");
                        echo "\">";
                        echo (isset($context["REPORTED_IMG"]) ? $context["REPORTED_IMG"] : null);
                        echo "</a>";
                    }
                    echo " ";
                    echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "ATTACH_ICON_IMG");
                    echo "<br />
\t\t\t\t\t\t";
                    // line 82
                    if ((isset($context["S_SHOW_RECIPIENTS"]) ? $context["S_SHOW_RECIPIENTS"] : null)) {
                        echo $this->env->getExtension('phpbb')->lang("MESSAGE_TO");
                        echo " ";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "RECIPIENTS");
                    } else {
                        echo $this->env->getExtension('phpbb')->lang("MESSAGE_BY_AUTHOR");
                        echo " ";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "MESSAGE_AUTHOR_FULL");
                        echo " &raquo; ";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "SENT_TIME");
                    }
                    // line 83
                    echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t";
                    // line 86
                    if ((isset($context["S_SHOW_RECIPIENTS"]) ? $context["S_SHOW_RECIPIENTS"] : null)) {
                        echo "<dd class=\"info\"><span>";
                        echo $this->env->getExtension('phpbb')->lang("SENT_AT");
                        echo $this->env->getExtension('phpbb')->lang("COLON");
                        echo " ";
                        echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "SENT_TIME");
                        echo "</span></dd>";
                    }
                    // line 87
                    echo "\t\t\t\t\t";
                    if ((isset($context["S_UNREAD"]) ? $context["S_UNREAD"] : null)) {
                        echo "<dd class=\"info\">";
                        if ($this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "FOLDER")) {
                            echo "<a href=\"";
                            echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "U_FOLDER");
                            echo "\">";
                            echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "FOLDER");
                            echo "</a>";
                        } else {
                            echo $this->env->getExtension('phpbb')->lang("UNKNOWN_FOLDER");
                        }
                        echo "</dd>";
                    }
                    // line 88
                    echo "\t\t\t\t\t<dd class=\"mark\"><input type=\"checkbox\" name=\"marked_msg_id[]\" value=\"";
                    echo $this->getAttribute((isset($context["messagerow"]) ? $context["messagerow"] : null), "MESSAGE_ID");
                    echo "\" /></dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['messagerow'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 92
                echo "
\t\t</ul>
\t";
            } else {
                // line 95
                echo "\t\t<p><strong>
\t\t\t";
                // line 96
                if (((isset($context["S_COMPOSE_PM_VIEW"]) ? $context["S_COMPOSE_PM_VIEW"] : null) && (isset($context["S_NO_AUTH_SEND_MESSAGE"]) ? $context["S_NO_AUTH_SEND_MESSAGE"] : null))) {
                    // line 97
                    echo "\t\t\t\t";
                    if ((isset($context["S_USER_NEW"]) ? $context["S_USER_NEW"] : null)) {
                        echo $this->env->getExtension('phpbb')->lang("USER_NEW_PERMISSION_DISALLOWED");
                    } else {
                        echo $this->env->getExtension('phpbb')->lang("NO_AUTH_SEND_MESSAGE");
                    }
                    // line 98
                    echo "\t\t\t";
                } else {
                    // line 99
                    echo "\t\t\t\t";
                    echo $this->env->getExtension('phpbb')->lang("NO_MESSAGES");
                    echo "
\t\t\t";
                }
                // line 101
                echo "\t\t</strong></p>
\t";
            }
            // line 103
            echo "
\t";
            // line 104
            if (((isset($context["FOLDER_CUR_MESSAGES"]) ? $context["FOLDER_CUR_MESSAGES"] : null) != 0)) {
                // line 105
                echo "\t\t<fieldset class=\"display-actions\">
\t\t\t<div class=\"left-box\"><label for=\"export_option\">";
                // line 106
                echo $this->env->getExtension('phpbb')->lang("EXPORT_FOLDER");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo " <select name=\"export_option\" id=\"export_option\"><option value=\"CSV\">";
                echo $this->env->getExtension('phpbb')->lang("EXPORT_AS_CSV");
                echo "</option><option value=\"CSV_EXCEL\">";
                echo $this->env->getExtension('phpbb')->lang("EXPORT_AS_CSV_EXCEL");
                echo "</option><option value=\"XML\">";
                echo $this->env->getExtension('phpbb')->lang("EXPORT_AS_XML");
                echo "</option></select></label> <input class=\"button2\" type=\"submit\" name=\"submit_export\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("GO");
                echo "\" /><br /></div>
\t\t\t<select name=\"mark_option\">";
                // line 107
                echo (isset($context["S_MARK_OPTIONS"]) ? $context["S_MARK_OPTIONS"] : null);
                echo (isset($context["S_MOVE_MARKED_OPTIONS"]) ? $context["S_MOVE_MARKED_OPTIONS"] : null);
                echo "</select> <input class=\"button2\" type=\"submit\" name=\"submit_mark\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("GO");
                echo "\" />
\t\t\t<div><a href=\"#\" onclick=\"marklist('viewfolder', 'marked_msg', true); return false;\">";
                // line 108
                echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
                echo "</a> &bull; <a href=\"#\" onclick=\"marklist('viewfolder', 'marked_msg', false); return false;\">";
                echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
                echo "</a></div>
\t\t</fieldset>
\t
\t\t<hr />
\t
\t\t<div class=\"action-bar bottom\">
\t\t\t<div class=\"pagination\">
\t\t\t\t";
                // line 115
                echo (isset($context["TOTAL_MESSAGES"]) ? $context["TOTAL_MESSAGES"] : null);
                echo "
\t\t\t\t";
                // line 116
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                    echo " 
\t\t\t\t\t";
                    // line 117
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
                    // line 118
                    echo "\t\t\t\t";
                } else {
                    echo " 
\t\t\t\t\t &bull; ";
                    // line 119
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t\t";
                }
                // line 121
                echo "\t\t\t</div>
\t\t</div>
\t";
            }
            // line 124
            echo "
\t\t</div>
\t</div>

\t";
            // line 128
            if (((isset($context["FOLDER_CUR_MESSAGES"]) ? $context["FOLDER_CUR_MESSAGES"] : null) != 0)) {
                // line 129
                echo "\t<fieldset class=\"display-options\">
\t\t<label>";
                // line 130
                echo $this->env->getExtension('phpbb')->lang("DISPLAY");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo " ";
                echo (isset($context["S_SELECT_SORT_DAYS"]) ? $context["S_SELECT_SORT_DAYS"] : null);
                echo "</label>
\t\t<label>";
                // line 131
                echo $this->env->getExtension('phpbb')->lang("SORT_BY");
                echo " ";
                echo (isset($context["S_SELECT_SORT_KEY"]) ? $context["S_SELECT_SORT_KEY"] : null);
                echo "</label>
\t\t<label>";
                // line 132
                echo (isset($context["S_SELECT_SORT_DIR"]) ? $context["S_SELECT_SORT_DIR"] : null);
                echo "</label>
\t\t<input type=\"submit\" name=\"sort\" value=\"";
                // line 133
                echo $this->env->getExtension('phpbb')->lang("GO");
                echo "\" class=\"button2\" />
\t\t<input type=\"hidden\" name=\"cur_folder_id\" value=\"";
                // line 134
                echo (isset($context["CUR_FOLDER_ID"]) ? $context["CUR_FOLDER_ID"] : null);
                echo "\" />
\t</fieldset>
\t";
            }
            // line 137
            echo "
\t";
            // line 138
            $location = "ucp_pm_message_footer.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->env->loadTemplate("ucp_pm_message_footer.html")->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
        // line 140
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
        return "ucp_pm_viewfolder.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  481 => 140,  468 => 138,  465 => 137,  459 => 134,  455 => 133,  451 => 132,  445 => 131,  438 => 130,  435 => 129,  433 => 128,  427 => 124,  422 => 121,  417 => 119,  412 => 118,  400 => 117,  396 => 116,  392 => 115,  380 => 108,  373 => 107,  360 => 106,  357 => 105,  355 => 104,  352 => 103,  348 => 101,  342 => 99,  339 => 98,  332 => 97,  330 => 96,  327 => 95,  322 => 92,  311 => 88,  296 => 87,  287 => 86,  282 => 83,  270 => 82,  258 => 81,  255 => 80,  249 => 78,  246 => 77,  238 => 75,  233 => 73,  226 => 72,  224 => 71,  220 => 69,  214 => 68,  206 => 67,  202 => 66,  189 => 65,  185 => 64,  176 => 62,  169 => 58,  165 => 57,  160 => 54,  158 => 53,  155 => 52,  147 => 49,  144 => 48,  142 => 47,  139 => 46,  133 => 43,  130 => 42,  128 => 41,  125 => 40,  117 => 35,  112 => 33,  100 => 25,  92 => 21,  86 => 18,  80 => 15,  75 => 14,  73 => 13,  70 => 12,  56 => 10,  54 => 9,  51 => 8,  37 => 7,  32 => 5,  24 => 3,  21 => 2,  19 => 1,);
    }
}
