<?php

/* mcp_front.html */
class __TwigTemplate_9350bae35bc2d1ab7d6565ce8e3369c9 extends Twig_Template
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
        $location = "mcp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("mcp_header.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<h2>";
        // line 3
        echo (isset($context["PAGE_TITLE"]) ? $context["PAGE_TITLE"] : null);
        echo "</h2>

";
        // line 5
        // line 6
        echo "
";
        // line 7
        if ((isset($context["S_SHOW_UNAPPROVED"]) ? $context["S_SHOW_UNAPPROVED"] : null)) {
            // line 8
            echo "
\t<form id=\"mcp_queue\" method=\"post\" action=\"";
            // line 9
            echo (isset($context["S_MCP_QUEUE_ACTION"]) ? $context["S_MCP_QUEUE_ACTION"] : null);
            echo "\">

\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 14
            echo $this->env->getExtension('phpbb')->lang("LATEST_UNAPPROVED");
            echo "</h3>
\t\t<p>";
            // line 15
            echo $this->env->getExtension('phpbb')->lang("UNAPPROVED_TOTAL");
            echo "</p>

\t\t";
            // line 17
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "unapproved"))) {
                // line 18
                echo "\t\t\t<ul class=\"topiclist missing-column\">
\t\t\t\t<li class=\"header\">
\t\t\t\t\t<dl>
\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 21
                echo $this->env->getExtension('phpbb')->lang("VIEW_DETAILS");
                echo "</div></dt>
\t\t\t\t\t\t<dd class=\"moderation\"><span>";
                // line 22
                echo $this->env->getExtension('phpbb')->lang("TOPIC");
                echo " &amp; ";
                echo $this->env->getExtension('phpbb')->lang("FORUM");
                echo "</span></dd>
\t\t\t\t\t</dl>
\t\t\t\t</li>
\t\t\t</ul>
\t\t\t<ul class=\"topiclist cplist missing-column responsive-show-all\">

\t\t\t";
                // line 28
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "unapproved"));
                foreach ($context['_seq'] as $context["_key"] => $context["unapproved"]) {
                    // line 29
                    echo "\t\t\t<li class=\"row";
                    if (($this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "S_ROW_COUNT") % 2 == 1)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t\t<dl>
\t\t\t\t\t<dt>
\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 33
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "U_POST_DETAILS");
                    echo "\" class=\"topictitle\">";
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "SUBJECT");
                    echo "</a> ";
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "ATTACH_ICON_IMG");
                    echo "<br />
\t\t\t\t\t\t\t";
                    // line 34
                    echo $this->env->getExtension('phpbb')->lang("POSTED");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "AUTHOR_FULL");
                    echo " &raquo; ";
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "POST_TIME");
                    echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t<dd class=\"moderation\"><span>
\t\t\t\t\t\t";
                    // line 38
                    echo $this->env->getExtension('phpbb')->lang("TOPIC");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <a href=\"";
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "U_TOPIC");
                    echo "\">";
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "TOPIC_TITLE");
                    echo "</a> [<a href=\"";
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "U_MCP_TOPIC");
                    echo "\">";
                    echo $this->env->getExtension('phpbb')->lang("MODERATE");
                    echo "</a>]<br />
\t\t\t\t\t\t";
                    // line 39
                    echo $this->env->getExtension('phpbb')->lang("FORUM");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " ";
                    if ($this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "U_FORUM")) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "U_FORUM");
                        echo "\">";
                        echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "FORUM_NAME");
                        echo "</a>";
                    } else {
                        echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "FORUM_NAME");
                    }
                    if ($this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "U_MCP_FORUM")) {
                        echo " [<a href=\"";
                        echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "U_MCP_FORUM");
                        echo "\">";
                        echo $this->env->getExtension('phpbb')->lang("MODERATE");
                        echo "</a>]";
                    }
                    echo "</span>
\t\t\t\t\t</dd>

\t\t\t \t\t<dd class=\"mark\"><input type=\"checkbox\" name=\"post_id_list[]\" value=\"";
                    // line 42
                    echo $this->getAttribute((isset($context["unapproved"]) ? $context["unapproved"] : null), "POST_ID");
                    echo "\" /></dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unapproved'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 46
                echo "\t\t\t</ul>
\t\t";
            }
            // line 48
            echo "
\t\t</div>
\t";
            // line 50
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t</div>

\t";
            // line 53
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "unapproved"))) {
                // line 54
                echo "\t<fieldset class=\"display-actions\">
\t\t";
                // line 55
                echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
                echo "
\t\t<input class=\"button2\" type=\"submit\" name=\"action[disapprove]\" value=\"";
                // line 56
                echo $this->env->getExtension('phpbb')->lang("DISAPPROVE");
                echo "\" />&nbsp;
\t\t<input class=\"button1\" type=\"submit\" name=\"action[approve]\" value=\"";
                // line 57
                echo $this->env->getExtension('phpbb')->lang("APPROVE");
                echo "\" />
\t\t<div><a href=\"#\" onclick=\"marklist('mcp_queue', 'post_id_list', true); return false;\">";
                // line 58
                echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
                echo "</a> :: <a href=\"#\" onclick=\"marklist('mcp_queue', 'post_id_list', false); return false;\">";
                echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
                echo "</a></div>
\t</fieldset>
\t";
            }
            // line 61
            echo "\t\t</form>
";
        }
        // line 63
        echo "
";
        // line 64
        // line 65
        echo "
";
        // line 66
        if ((isset($context["S_SHOW_REPORTS"]) ? $context["S_SHOW_REPORTS"] : null)) {
            // line 67
            echo "\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 70
            echo $this->env->getExtension('phpbb')->lang("LATEST_REPORTED");
            echo "</h3>
\t\t<p>";
            // line 71
            echo $this->env->getExtension('phpbb')->lang("REPORTS_TOTAL");
            echo "</p>

\t\t";
            // line 73
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "report"))) {
                // line 74
                echo "\t\t\t<ul class=\"topiclist two-long-columns\">
\t\t\t\t<li class=\"header\">
\t\t\t\t\t<dl>
\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 77
                echo $this->env->getExtension('phpbb')->lang("VIEW_DETAILS");
                echo "</div></dt>
\t\t\t\t\t\t<dd class=\"moderation\"><span>";
                // line 78
                echo $this->env->getExtension('phpbb')->lang("REPORTER");
                echo " &amp; ";
                echo $this->env->getExtension('phpbb')->lang("FORUM");
                echo "</span></dd>
\t\t\t\t\t</dl>
\t\t\t\t</li>
\t\t\t</ul>
\t\t\t<ul class=\"topiclist cplist two-long-columns responsive-show-all\">

\t\t\t";
                // line 84
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "report"));
                foreach ($context['_seq'] as $context["_key"] => $context["report"]) {
                    // line 85
                    echo "\t\t\t<li class=\"row";
                    if (($this->getAttribute((isset($context["report"]) ? $context["report"] : null), "S_ROW_COUNT") % 2 == 1)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t\t<dl>
\t\t\t\t\t<dt>
\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 89
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "U_POST_DETAILS");
                    echo "#reports\" class=\"topictitle\">";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "SUBJECT");
                    echo "</a> ";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "ATTACH_ICON_IMG");
                    echo "<br />
\t\t\t\t\t\t\t<span>";
                    // line 90
                    echo $this->env->getExtension('phpbb')->lang("POSTED");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "AUTHOR_FULL");
                    echo " &raquo; ";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "POST_TIME");
                    echo "</span>
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t<dd class=\"moderation\">
\t\t\t\t\t\t<span>";
                    // line 94
                    echo $this->env->getExtension('phpbb')->lang("REPORTED");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "REPORTER_FULL");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("REPORTED_ON_DATE");
                    echo " ";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "REPORT_TIME");
                    echo "<br />
\t\t\t\t\t\t";
                    // line 95
                    echo $this->env->getExtension('phpbb')->lang("FORUM");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <a href=\"";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "U_FORUM");
                    echo "\">";
                    echo $this->getAttribute((isset($context["report"]) ? $context["report"] : null), "FORUM_NAME");
                    echo "</a></span>
\t\t\t\t\t</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['report'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 100
                echo "\t\t\t</ul>
\t\t";
            }
            // line 102
            echo "
\t\t</div>
\t</div>
";
        }
        // line 106
        echo "
";
        // line 107
        // line 108
        echo "
";
        // line 109
        if ((isset($context["S_SHOW_PM_REPORTS"]) ? $context["S_SHOW_PM_REPORTS"] : null)) {
            // line 110
            echo "\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 113
            echo $this->env->getExtension('phpbb')->lang("LATEST_REPORTED_PMS");
            echo "</h3>
\t\t<p>";
            // line 114
            echo $this->env->getExtension('phpbb')->lang("PM_REPORTS_TOTAL");
            echo "</p>

\t\t";
            // line 116
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pm_report"))) {
                // line 117
                echo "\t\t\t<ul class=\"topiclist two-long-columns\">
\t\t\t\t<li class=\"header\">
\t\t\t\t\t<dl>
\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 120
                echo $this->env->getExtension('phpbb')->lang("VIEW_DETAILS");
                echo "</div></dt>
\t\t\t\t\t\t<dd class=\"moderation\"><span>";
                // line 121
                echo $this->env->getExtension('phpbb')->lang("REPORTER");
                echo "</span></dd>
\t\t\t\t\t</dl>
\t\t\t\t</li>
\t\t\t</ul>
\t\t\t<ul class=\"topiclist cplist two-long-columns responsive-show-all\">

\t\t\t";
                // line 127
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pm_report"));
                foreach ($context['_seq'] as $context["_key"] => $context["pm_report"]) {
                    // line 128
                    echo "\t\t\t<li class=\"row";
                    if (($this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "S_ROW_COUNT") % 2 == 1)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t\t<dl>
\t\t\t\t\t<dt>
\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 132
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "U_PM_DETAILS");
                    echo "\" class=\"topictitle\">";
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "PM_SUBJECT");
                    echo "</a> ";
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "ATTACH_ICON_IMG");
                    echo "<br />
\t\t\t\t\t\t\t<span>";
                    // line 133
                    echo $this->env->getExtension('phpbb')->lang("MESSAGE_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "PM_AUTHOR_FULL");
                    echo " &raquo; ";
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "PM_TIME");
                    echo "</span><br />
\t\t\t\t\t\t\t<span>";
                    // line 134
                    echo $this->env->getExtension('phpbb')->lang("MESSAGE_TO");
                    echo " ";
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "RECIPIENTS");
                    echo "</span>
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t<dd class=\"moderation\">
\t\t\t\t\t\t<span>";
                    // line 138
                    echo $this->env->getExtension('phpbb')->lang("REPORTED");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "REPORTER_FULL");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("REPORTED_ON_DATE");
                    echo " ";
                    echo $this->getAttribute((isset($context["pm_report"]) ? $context["pm_report"] : null), "REPORT_TIME");
                    echo "</span>
\t\t\t\t\t</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pm_report'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 143
                echo "\t\t\t</ul>
\t\t";
            }
            // line 145
            echo "
\t\t</div>
\t</div>
";
        }
        // line 149
        echo "
";
        // line 150
        // line 151
        echo "
";
        // line 152
        if ((isset($context["S_SHOW_LOGS"]) ? $context["S_SHOW_LOGS"] : null)) {
            // line 153
            echo "\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 156
            echo $this->env->getExtension('phpbb')->lang("LATEST_LOGS");
            echo "</h3>

\t\t<table class=\"table1\">
\t\t<thead>
\t\t<tr>
\t\t\t<th class=\"name\">";
            // line 161
            echo $this->env->getExtension('phpbb')->lang("ACTION");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 162
            echo $this->env->getExtension('phpbb')->lang("USERNAME");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 163
            echo $this->env->getExtension('phpbb')->lang("IP");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 164
            echo $this->env->getExtension('phpbb')->lang("VIEW_TOPIC");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 165
            echo $this->env->getExtension('phpbb')->lang("VIEW_TOPIC_LOGS");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 166
            echo $this->env->getExtension('phpbb')->lang("TIME");
            echo "</th>
\t\t</tr>
\t\t</thead>
\t\t<tbody>
\t";
            // line 170
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "log"));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                // line 171
                echo "\t\t<tr class=\"";
                if (($this->getAttribute((isset($context["log"]) ? $context["log"] : null), "S_ROW_COUNT") % 2 == 0)) {
                    echo "bg1";
                } else {
                    echo "bg2";
                }
                echo "\">
\t\t\t<td>";
                // line 172
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "ACTION");
                echo "</td>
\t\t\t<td><span>";
                // line 173
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "USERNAME");
                echo "</span></td>
\t\t\t<td><span>";
                // line 174
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "IP");
                echo "</span></td>
\t\t\t<td><span>";
                // line 175
                if ($this->getAttribute((isset($context["log"]) ? $context["log"] : null), "U_VIEW_TOPIC")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "U_VIEW_TOPIC");
                    echo "\" title=\"";
                    echo $this->env->getExtension('phpbb')->lang("VIEW_TOPIC");
                    echo "\">";
                    echo $this->env->getExtension('phpbb')->lang("VIEW_TOPIC");
                    echo "</a>";
                }
                echo "&nbsp;</span></td>
\t\t\t<td><span>";
                // line 176
                if ($this->getAttribute((isset($context["log"]) ? $context["log"] : null), "U_VIEWLOGS")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "U_VIEWLOGS");
                    echo "\">";
                    echo $this->env->getExtension('phpbb')->lang("VIEW_TOPIC_LOGS");
                    echo "</a>";
                }
                echo "&nbsp;</span></td>
\t\t\t<td><span>";
                // line 177
                echo $this->getAttribute((isset($context["log"]) ? $context["log"] : null), "TIME");
                echo "</span></td>
\t\t</tr>
\t";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 180
                echo "\t\t<tr>
\t\t\t<td colspan=\"6\">";
                // line 181
                echo $this->env->getExtension('phpbb')->lang("NO_ENTRIES");
                echo "</td>
\t\t</tr>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 184
            echo "\t\t</tbody>
\t\t</table>

\t\t</div>
\t</div>
";
        }
        // line 190
        echo "
";
        // line 191
        // line 192
        echo "
";
        // line 193
        $location = "mcp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("mcp_footer.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "mcp_front.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  579 => 193,  576 => 192,  575 => 191,  572 => 190,  564 => 184,  555 => 181,  552 => 180,  544 => 177,  534 => 176,  522 => 175,  518 => 174,  514 => 173,  510 => 172,  501 => 171,  496 => 170,  489 => 166,  485 => 165,  481 => 164,  477 => 163,  473 => 162,  469 => 161,  461 => 156,  456 => 153,  454 => 152,  451 => 151,  450 => 150,  447 => 149,  441 => 145,  437 => 143,  418 => 138,  409 => 134,  401 => 133,  393 => 132,  381 => 128,  377 => 127,  368 => 121,  364 => 120,  359 => 117,  357 => 116,  352 => 114,  348 => 113,  343 => 110,  341 => 109,  338 => 108,  337 => 107,  334 => 106,  328 => 102,  324 => 100,  308 => 95,  296 => 94,  283 => 90,  275 => 89,  263 => 85,  259 => 84,  248 => 78,  244 => 77,  239 => 74,  237 => 73,  232 => 71,  228 => 70,  223 => 67,  221 => 66,  218 => 65,  217 => 64,  214 => 63,  210 => 61,  202 => 58,  198 => 57,  194 => 56,  190 => 55,  187 => 54,  185 => 53,  179 => 50,  175 => 48,  171 => 46,  161 => 42,  137 => 39,  124 => 38,  111 => 34,  103 => 33,  91 => 29,  87 => 28,  76 => 22,  72 => 21,  67 => 18,  65 => 17,  60 => 15,  56 => 14,  48 => 9,  45 => 8,  43 => 7,  40 => 6,  39 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }
}
