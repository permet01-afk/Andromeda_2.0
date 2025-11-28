<?php

/* ucp_main_subscribed.html */
class __TwigTemplate_c3be57b2df891d7457e3656d41e30370 extends Twig_Template
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
<form id=\"ucp\" method=\"post\" action=\"";
        // line 3
        echo (isset($context["S_UCP_ACTION"]) ? $context["S_UCP_ACTION"] : null);
        echo "\"";
        echo (isset($context["S_FORM_ENCTYPE"]) ? $context["S_FORM_ENCTYPE"] : null);
        echo ">

<h2>";
        // line 5
        echo $this->env->getExtension('phpbb')->lang("TITLE");
        echo "</h2>
<div class=\"panel\">
\t<div class=\"inner\">

\t<p>";
        // line 9
        echo $this->env->getExtension('phpbb')->lang("WATCHED_EXPLAIN");
        echo "</p>

";
        // line 11
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "forumrow"))) {
            // line 12
            echo "\t<ul class=\"topiclist missing-column\">
\t\t<li class=\"header\">
\t\t\t<dl class=\"icon\">
\t\t\t\t<dt><div class=\"list-inner\">";
            // line 15
            echo $this->env->getExtension('phpbb')->lang("WATCHED_FORUMS");
            echo "</div></dt>
\t\t\t\t<dd class=\"lastpost\"><span>";
            // line 16
            echo $this->env->getExtension('phpbb')->lang("LAST_POST");
            echo "</span></dd>
\t\t\t\t<dd class=\"mark\">";
            // line 17
            echo $this->env->getExtension('phpbb')->lang("MARK");
            echo "</dd>
\t\t\t</dl>
\t\t</li>
\t</ul>
\t<ul class=\"topiclist cplist missing-column\">

\t";
            // line 23
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "forumrow"));
            foreach ($context['_seq'] as $context["_key"] => $context["forumrow"]) {
                // line 24
                echo "\t\t<li class=\"row";
                if (($this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "S_ROW_COUNT") % 2 == 1)) {
                    echo " bg1";
                } else {
                    echo " bg2";
                }
                echo "\">
\t\t\t<dl class=\"icon ";
                // line 25
                echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "FORUM_IMG_STYLE");
                echo "\">
\t\t\t\t<dt>
\t\t\t\t\t";
                // line 27
                if ($this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "S_UNREAD_FORUM")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "U_VIEWFORUM");
                    echo "\" class=\"icon-link\"></a>";
                }
                // line 28
                echo "\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t<a href=\"";
                // line 29
                echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "U_VIEWFORUM");
                echo "\" class=\"forumtitle\">";
                echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "FORUM_NAME");
                echo "</a><br />
\t\t\t\t\t\t";
                // line 30
                echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "FORUM_DESC");
                echo "
\t\t\t\t\t\t";
                // line 31
                if ($this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "LAST_POST_TIME")) {
                    // line 32
                    echo "\t\t\t\t\t\t<div class=\"responsive-show\" style=\"display: none;\">
\t\t\t\t\t\t\t";
                    // line 33
                    echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "LAST_POST_AUTHOR_FULL");
                    echo " &laquo; <a href=\"";
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_LAST_POST");
                    echo "\">";
                    echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "LAST_POST_TIME");
                    echo "</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t";
                }
                // line 36
                echo "\t\t\t\t\t</div>
\t\t\t\t</dt>
\t\t\t\t<dd class=\"lastpost\">";
                // line 38
                if ($this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "LAST_POST_TIME")) {
                    echo "<span><dfn>";
                    echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                    echo " </dfn>";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "LAST_POST_AUTHOR_FULL");
                    echo "
\t\t\t\t\t<a href=\"";
                    // line 39
                    echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "U_LAST_POST");
                    echo "\">";
                    echo (isset($context["LAST_POST_IMG"]) ? $context["LAST_POST_IMG"] : null);
                    echo "</a> <br />";
                    echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "LAST_POST_TIME");
                    echo "</span>
\t\t\t\t\t";
                } else {
                    // line 40
                    echo $this->env->getExtension('phpbb')->lang("NO_POSTS");
                    echo "<br />&nbsp;";
                }
                // line 41
                echo "\t\t\t\t</dd>
\t\t\t\t<dd class=\"mark\"><input type=\"checkbox\" name=\"f[";
                // line 42
                echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "FORUM_ID");
                echo "]\" id=\"f";
                echo $this->getAttribute((isset($context["forumrow"]) ? $context["forumrow"] : null), "FORUM_ID");
                echo "\" /></dd>
\t\t\t</dl>
\t\t</li>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['forumrow'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 46
            echo "\t</ul>
";
        } elseif ((isset($context["S_FORUM_NOTIFY"]) ? $context["S_FORUM_NOTIFY"] : null)) {
            // line 48
            echo "    <ul class=\"topiclist\">
        <li class=\"header\">
            <dl class=\"icon\">
                <dt>";
            // line 51
            echo $this->env->getExtension('phpbb')->lang("WATCHED_FORUMS");
            echo "</dt>
            </dl>
        </li>
    </ul>
\t<p><strong>";
            // line 55
            echo $this->env->getExtension('phpbb')->lang("NO_WATCHED_FORUMS");
            echo "</strong></p>
";
        }
        // line 57
        echo "    <br />

";
        // line 59
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "topicrow"))) {
            // line 60
            echo "\t<ul class=\"topiclist missing-column\">
\t\t<li class=\"header\">
\t\t\t<dl class=\"icon\">
\t\t\t\t<dt><div class=\"list-inner\">";
            // line 63
            echo $this->env->getExtension('phpbb')->lang("WATCHED_TOPICS");
            echo "</div></dt>
\t\t\t\t<dd class=\"lastpost\"><span>";
            // line 64
            echo $this->env->getExtension('phpbb')->lang("LAST_POST");
            echo "</span></dd>
\t\t\t\t<dd class=\"mark\">";
            // line 65
            echo $this->env->getExtension('phpbb')->lang("MARK");
            echo "</dd>
\t\t\t</dl>
\t\t</li>
\t</ul>
\t<ul class=\"topiclist cplist missing-column\">

\t";
            // line 71
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "topicrow"));
            foreach ($context['_seq'] as $context["_key"] => $context["topicrow"]) {
                // line 72
                echo "\t\t<li class=\"row";
                if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_TOPIC_REPORTED")) {
                    echo " reported";
                } elseif (($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_ROW_COUNT") % 2 == 1)) {
                    echo " bg1";
                } else {
                    echo " bg2";
                }
                echo "\">
\t\t\t<dl class=\"icon ";
                // line 73
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_IMG_STYLE");
                echo "\">
\t\t\t\t<dt";
                // line 74
                if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ICON_IMG")) {
                    echo " style=\"background-image: url(";
                    echo (isset($context["T_ICONS_PATH"]) ? $context["T_ICONS_PATH"] : null);
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ICON_IMG");
                    echo "); background-repeat: no-repeat;\"";
                }
                echo " title=\"";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_FOLDER_IMG_ALT");
                echo "\">
\t\t\t\t\t";
                // line 75
                if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_UNREAD_TOPIC")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_NEWEST_POST");
                    echo "\" class=\"icon-link\"></a>";
                }
                // line 76
                echo "\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t";
                // line 77
                if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_UNREAD_TOPIC")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_NEWEST_POST");
                    echo "\">";
                    echo (isset($context["NEWEST_POST_IMG"]) ? $context["NEWEST_POST_IMG"] : null);
                    echo "</a> ";
                }
                echo "<a href=\"";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_VIEW_TOPIC");
                echo "\" class=\"topictitle\">";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_TITLE");
                echo "</a>
\t\t\t\t\t\t";
                // line 78
                if (($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_TOPIC_UNAPPROVED") || $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_POSTS_UNAPPROVED"))) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_MCP_QUEUE");
                    echo "\">";
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "UNAPPROVED_IMG");
                    echo "</a> ";
                }
                // line 79
                echo "\t\t\t\t\t\t";
                if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_TOPIC_REPORTED")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_MCP_REPORT");
                    echo "\">";
                    echo (isset($context["REPORTED_IMG"]) ? $context["REPORTED_IMG"] : null);
                    echo "</a>";
                }
                echo "<br />
\t\t\t\t\t\t";
                // line 80
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "pagination"))) {
                    // line 81
                    echo "\t\t\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t\t\t<ul>
\t\t\t\t\t\t\t";
                    // line 83
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "pagination"));
                    foreach ($context['_seq'] as $context["_key"] => $context["pagination"]) {
                        // line 84
                        echo "\t\t\t\t\t\t\t\t";
                        if ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_PREV")) {
                            // line 85
                            echo "\t\t\t\t\t\t\t\t";
                        } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_CURRENT")) {
                            echo "<li class=\"active\"><span>";
                            echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_NUMBER");
                            echo "</span></li>
\t\t\t\t\t\t\t\t";
                        } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_ELLIPSIS")) {
                            // line 86
                            echo "<li class=\"ellipsis\"><span>";
                            echo $this->env->getExtension('phpbb')->lang("ELLIPSIS");
                            echo "</span></li>
\t\t\t\t\t\t\t\t";
                        } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_NEXT")) {
                            // line 88
                            echo "\t\t\t\t\t\t\t\t";
                        } else {
                            echo "<li><a href=\"";
                            echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_URL");
                            echo "\">";
                            echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_NUMBER");
                            echo "</a></li>
\t\t\t\t\t\t\t\t";
                        }
                        // line 90
                        echo "\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pagination'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 91
                    echo "\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t";
                }
                // line 94
                echo "\t\t\t\t\t\t<div class=\"responsive-hide\">
\t\t\t\t\t\t\t";
                // line 95
                if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG")) {
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG");
                    echo " ";
                }
                // line 96
                echo "\t\t\t\t\t\t\t";
                echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                echo " ";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_AUTHOR_FULL");
                echo " &raquo; ";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "FIRST_POST_TIME");
                echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"responsive-show\" style=\"display: none;\">
\t\t\t\t\t\t\t";
                // line 99
                if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG")) {
                    echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG");
                    echo " ";
                }
                // line 100
                echo "\t\t\t\t\t\t\t";
                echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                echo " ";
                echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                echo " ";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "LAST_POST_AUTHOR_FULL");
                echo " &laquo; <a href=\"";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_LAST_POST");
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("GOTO_LAST_POST");
                echo "\">";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "LAST_POST_TIME");
                echo "</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</dt>
\t\t\t\t<dd class=\"lastpost\"><span><dfn>";
                // line 104
                echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                echo " </dfn>";
                echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                echo " ";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "LAST_POST_AUTHOR_FULL");
                echo "
\t\t\t\t\t<a href=\"";
                // line 105
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_LAST_POST");
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("GOTO_LAST_POST");
                echo "\">";
                echo (isset($context["LAST_POST_IMG"]) ? $context["LAST_POST_IMG"] : null);
                echo "</a> <br />";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "LAST_POST_TIME");
                echo "</span>
\t\t\t\t</dd>
\t\t\t\t<dd class=\"mark\"><input type=\"checkbox\" name=\"t[";
                // line 107
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ID");
                echo "]\" id=\"t";
                echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ID");
                echo "\" /></dd>
\t\t\t</dl>
\t\t</li>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['topicrow'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 111
            echo "\t</ul>

\t<div class=\"action-bar bottom\">
\t\t<div class=\"pagination\">
\t\t\t";
            // line 115
            echo (isset($context["TOTAL_TOPICS"]) ? $context["TOTAL_TOPICS"] : null);
            echo "
\t\t\t";
            // line 116
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                echo " 
\t\t\t\t";
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
                echo "\t\t\t";
            } else {
                echo " 
\t\t\t\t &bull; ";
                // line 119
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t";
            }
            // line 121
            echo "\t\t</div>
\t</div>

";
        } elseif ((isset($context["S_TOPIC_NOTIFY"]) ? $context["S_TOPIC_NOTIFY"] : null)) {
            // line 125
            echo "    <ul class=\"topiclist\">
        <li class=\"header\">
            <dl class=\"icon\">
                <dt>";
            // line 128
            echo $this->env->getExtension('phpbb')->lang("WATCHED_TOPICS");
            echo "</dt>
            </dl>
        </li>
    </ul>
\t<p><strong>";
            // line 132
            echo $this->env->getExtension('phpbb')->lang("NO_WATCHED_TOPICS");
            echo "</strong></p>
";
        }
        // line 134
        echo "
\t</div>
</div>

";
        // line 138
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "topicrow")) || twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "forumrow")))) {
            // line 139
            echo "\t<fieldset class=\"display-actions\">
\t\t<input type=\"submit\" name=\"unwatch\" value=\"";
            // line 140
            echo $this->env->getExtension('phpbb')->lang("UNWATCH_MARKED");
            echo "\" class=\"button2\" />
\t\t<div><a href=\"#\" onclick=\"marklist('ucp', 't', true); marklist('ucp', 'f', true); return false;\">";
            // line 141
            echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"marklist('ucp', 't', false); marklist('ucp', 'f', false); return false;\">";
            echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
            echo "</a></div>
\t\t";
            // line 142
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t</fieldset>
";
        }
        // line 145
        echo "</form>

";
        // line 147
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
        return "ucp_main_subscribed.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  507 => 147,  503 => 145,  497 => 142,  491 => 141,  487 => 140,  484 => 139,  482 => 138,  476 => 134,  471 => 132,  464 => 128,  459 => 125,  453 => 121,  448 => 119,  443 => 118,  431 => 117,  427 => 116,  423 => 115,  417 => 111,  405 => 107,  394 => 105,  386 => 104,  368 => 100,  363 => 99,  352 => 96,  347 => 95,  344 => 94,  339 => 91,  333 => 90,  323 => 88,  317 => 86,  309 => 85,  306 => 84,  302 => 83,  298 => 81,  296 => 80,  285 => 79,  277 => 78,  263 => 77,  260 => 76,  254 => 75,  243 => 74,  239 => 73,  228 => 72,  224 => 71,  215 => 65,  211 => 64,  207 => 63,  202 => 60,  200 => 59,  196 => 57,  191 => 55,  184 => 51,  179 => 48,  175 => 46,  163 => 42,  160 => 41,  156 => 40,  147 => 39,  137 => 38,  133 => 36,  119 => 33,  116 => 32,  114 => 31,  110 => 30,  104 => 29,  101 => 28,  95 => 27,  90 => 25,  81 => 24,  77 => 23,  68 => 17,  64 => 16,  60 => 15,  55 => 12,  53 => 11,  48 => 9,  41 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }
}
