<?php

/* ucp_main_bookmarks.html */
class __TwigTemplate_5aa0b910a43a3253ee889d2a08cc438f extends Twig_Template
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
        // line 10
        echo $this->env->getExtension('phpbb')->lang("BOOKMARKS_EXPLAIN");
        echo "</p>

";
        // line 12
        if ((isset($context["S_NO_DISPLAY_BOOKMARKS"]) ? $context["S_NO_DISPLAY_BOOKMARKS"] : null)) {
            // line 13
            echo "\t<p class=\"error\">";
            echo $this->env->getExtension('phpbb')->lang("BOOKMARKS_DISABLED");
            echo "</p>
";
        } else {
            // line 15
            echo "
";
            // line 16
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "topicrow"))) {
                // line 17
                echo "\t<ul class=\"topiclist missing-column\">
\t\t<li class=\"header\">
\t\t\t<dl class=\"icon\">
\t\t\t\t<dt><div class=\"list-inner\">";
                // line 20
                echo $this->env->getExtension('phpbb')->lang("BOOKMARKS");
                echo "</div></dt>
\t\t\t\t<dd class=\"lastpost\"><span>";
                // line 21
                echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                echo "</span></dd>
\t\t\t\t<dd class=\"mark\">";
                // line 22
                echo $this->env->getExtension('phpbb')->lang("MARK");
                echo "</dd>
\t\t\t</dl>
\t\t</li>
\t</ul>
\t<ul class=\"topiclist cplist missing-column\">

\t";
                // line 28
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "topicrow"));
                foreach ($context['_seq'] as $context["_key"] => $context["topicrow"]) {
                    // line 29
                    echo "\t\t<li class=\"row";
                    if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_TOPIC_REPORTED")) {
                        echo " reported";
                    } elseif (($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_ROW_COUNT") % 2 == 1)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t";
                    // line 30
                    if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_DELETED_TOPIC")) {
                        // line 31
                        echo "\t\t\t\t<dl>
\t\t\t\t\t<dt><div class=\"list-inner\"><strong>";
                        // line 32
                        echo $this->env->getExtension('phpbb')->lang("DELETED_TOPIC");
                        echo "</strong></div></dt>
\t\t\t\t\t<dd class=\"lastpost\"><span>&nbsp;</span></dd>
\t\t\t\t\t<dd class=\"mark\"><input type=\"checkbox\" name=\"t[";
                        // line 34
                        echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ID");
                        echo "]\" id=\"t";
                        echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ID");
                        echo "\" /></dd>
\t\t\t\t</dl>
\t\t\t";
                    } else {
                        // line 37
                        echo "\t\t\t<dl class=\"icon ";
                        echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_IMG_STYLE");
                        echo "\">
\t\t\t\t<dt";
                        // line 38
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
                        // line 39
                        if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_UNREAD_TOPIC")) {
                            echo "<a href=\"";
                            echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_NEWEST_POST");
                            echo "\" class=\"icon-link\"></a>";
                        }
                        // line 40
                        echo "\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t";
                        // line 41
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
                        // line 42
                        if (($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_TOPIC_UNAPPROVED") || $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "S_POSTS_UNAPPROVED"))) {
                            echo "<a href=\"";
                            echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "U_MCP_QUEUE");
                            echo "\">";
                            echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "UNAPPROVED_IMG");
                            echo "</a> ";
                        }
                        // line 43
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
                        // line 44
                        if (twig_length_filter($this->env, $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "pagination"))) {
                            // line 45
                            echo "\t\t\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t\t\t<ul>
\t\t\t\t\t\t\t";
                            // line 47
                            $context['_parent'] = (array) $context;
                            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "pagination"));
                            foreach ($context['_seq'] as $context["_key"] => $context["pagination"]) {
                                // line 48
                                echo "\t\t\t\t\t\t\t\t";
                                if ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_PREV")) {
                                    // line 49
                                    echo "\t\t\t\t\t\t\t\t";
                                } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_CURRENT")) {
                                    echo "<li class=\"active\"><span>";
                                    echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_NUMBER");
                                    echo "</span></li>
\t\t\t\t\t\t\t\t";
                                } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_ELLIPSIS")) {
                                    // line 50
                                    echo "<li class=\"ellipsis\"><span>";
                                    echo $this->env->getExtension('phpbb')->lang("ELLIPSIS");
                                    echo "</span></li>
\t\t\t\t\t\t\t\t";
                                } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_NEXT")) {
                                    // line 52
                                    echo "\t\t\t\t\t\t\t\t";
                                } else {
                                    echo "<li><a href=\"";
                                    echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_URL");
                                    echo "\">";
                                    echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_NUMBER");
                                    echo "</a></li>
\t\t\t\t\t\t\t\t";
                                }
                                // line 54
                                echo "\t\t\t\t\t\t\t";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pagination'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 55
                            echo "\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t";
                        }
                        // line 58
                        echo "\t\t\t\t\t\t<div class=\"responsive-hide\">
\t\t\t\t\t\t\t";
                        // line 59
                        if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG")) {
                            echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG");
                            echo " ";
                        }
                        // line 60
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
                        // line 63
                        if ($this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG")) {
                            echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "ATTACH_ICON_IMG");
                            echo " ";
                        }
                        // line 64
                        echo "\t\t\t\t\t\t\t";
                        echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                        echo " ";
                        echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                        echo " ";
                        echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "LAST_POST_AUTHOR_FULL");
                        echo " &laquo;
\t\t\t\t\t\t\t<a href=\"";
                        // line 65
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
                        // line 69
                        echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                        echo " </dfn>";
                        echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                        echo " ";
                        echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "LAST_POST_AUTHOR_FULL");
                        echo "
\t\t\t\t\t<a href=\"";
                        // line 70
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
                        // line 72
                        echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ID");
                        echo "]\" id=\"t";
                        echo $this->getAttribute((isset($context["topicrow"]) ? $context["topicrow"] : null), "TOPIC_ID");
                        echo "\" /></dd>
\t\t\t</dl>
\t\t\t";
                    }
                    // line 75
                    echo "\t\t</li>
\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['topicrow'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 77
                echo "\t</ul>

\t<div class=\"action-bar bottom\">
\t\t<div class=\"pagination\">
\t\t\t";
                // line 81
                echo (isset($context["TOTAL_TOPICS"]) ? $context["TOTAL_TOPICS"] : null);
                echo "
\t\t\t";
                // line 82
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                    echo " 
\t\t\t\t";
                    // line 83
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
                    // line 84
                    echo "\t\t\t";
                } else {
                    echo " 
\t\t\t\t &bull; ";
                    // line 85
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t";
                }
                // line 87
                echo "\t\t</div>
\t</div>

";
            } else {
                // line 91
                echo "\t<p><strong>";
                echo $this->env->getExtension('phpbb')->lang("NO_BOOKMARKS");
                echo "</strong></p>
";
            }
            // line 93
            echo "
";
        }
        // line 95
        echo "
\t</div>
</div>

";
        // line 99
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "topicrow")) && (!(isset($context["S_NO_DISPLAY_BOOKMARKS"]) ? $context["S_NO_DISPLAY_BOOKMARKS"] : null)))) {
            // line 100
            echo "\t<fieldset class=\"display-actions\">
\t\t<input type=\"submit\" name=\"unbookmark\" value=\"";
            // line 101
            echo $this->env->getExtension('phpbb')->lang("REMOVE_BOOKMARK_MARKED");
            echo "\" class=\"button2\" />
\t\t<div><a href=\"#\" onclick=\"marklist('ucp', '', true); return false;\">";
            // line 102
            echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"marklist('ucp', '', false); return false;\">";
            echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
            echo "</a></div>
\t\t";
            // line 103
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t</fieldset>
";
        }
        // line 106
        echo "</form>

";
        // line 108
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
        return "ucp_main_bookmarks.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  389 => 108,  385 => 106,  379 => 103,  373 => 102,  369 => 101,  366 => 100,  364 => 99,  358 => 95,  354 => 93,  348 => 91,  342 => 87,  337 => 85,  332 => 84,  320 => 83,  316 => 82,  312 => 81,  306 => 77,  299 => 75,  291 => 72,  280 => 70,  272 => 69,  261 => 65,  252 => 64,  247 => 63,  236 => 60,  231 => 59,  228 => 58,  223 => 55,  217 => 54,  207 => 52,  201 => 50,  193 => 49,  190 => 48,  186 => 47,  182 => 45,  180 => 44,  169 => 43,  161 => 42,  147 => 41,  144 => 40,  138 => 39,  127 => 38,  122 => 37,  114 => 34,  109 => 32,  106 => 31,  104 => 30,  93 => 29,  89 => 28,  80 => 22,  76 => 21,  72 => 20,  67 => 17,  65 => 16,  62 => 15,  56 => 13,  54 => 12,  49 => 10,  41 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }
}
