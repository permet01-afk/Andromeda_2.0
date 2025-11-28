<?php

/* search_results.html */
class __TwigTemplate_f6bc982f411c324cd4b7dfad85ef3b1c extends Twig_Template
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
<h2 class=\"searchresults-title\">";
        // line 3
        if ((isset($context["SEARCH_TITLE"]) ? $context["SEARCH_TITLE"] : null)) {
            echo (isset($context["SEARCH_TITLE"]) ? $context["SEARCH_TITLE"] : null);
        } else {
            echo (isset($context["SEARCH_MATCHES"]) ? $context["SEARCH_MATCHES"] : null);
        }
        if ((isset($context["SEARCH_WORDS"]) ? $context["SEARCH_WORDS"] : null)) {
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " <a href=\"";
            echo (isset($context["U_SEARCH_WORDS"]) ? $context["U_SEARCH_WORDS"] : null);
            echo "\">";
            echo (isset($context["SEARCH_WORDS"]) ? $context["SEARCH_WORDS"] : null);
            echo "</a>";
        }
        echo "</h2>
";
        // line 4
        if ((isset($context["SEARCHED_QUERY"]) ? $context["SEARCHED_QUERY"] : null)) {
            echo " <p>";
            echo $this->env->getExtension('phpbb')->lang("SEARCHED_QUERY");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " <strong>";
            echo (isset($context["SEARCHED_QUERY"]) ? $context["SEARCHED_QUERY"] : null);
            echo "</strong></p>";
        }
        // line 5
        if ((isset($context["IGNORED_WORDS"]) ? $context["IGNORED_WORDS"] : null)) {
            echo " <p>";
            echo $this->env->getExtension('phpbb')->lang("IGNORED_TERMS");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " <strong>";
            echo (isset($context["IGNORED_WORDS"]) ? $context["IGNORED_WORDS"] : null);
            echo "</strong></p>";
        }
        // line 6
        if ((isset($context["PHRASE_SEARCH_DISABLED"]) ? $context["PHRASE_SEARCH_DISABLED"] : null)) {
            echo " <p><strong>";
            echo $this->env->getExtension('phpbb')->lang("PHRASE_SEARCH_DISABLED");
            echo "</strong></p>";
        }
        // line 7
        echo "
";
        // line 8
        if ((isset($context["SEARCH_TOPIC"]) ? $context["SEARCH_TOPIC"] : null)) {
            // line 9
            echo "\t<p><a class=\"arrow-";
            echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
            echo "\" href=\"";
            echo (isset($context["U_SEARCH_TOPIC"]) ? $context["U_SEARCH_TOPIC"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("RETURN_TO_TOPIC");
            echo "</a></p>
";
        } else {
            // line 11
            echo "\t<p><a class=\"arrow-";
            echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
            echo "\" href=\"";
            echo (isset($context["U_SEARCH"]) ? $context["U_SEARCH"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb')->lang("SEARCH_ADV");
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("GO_TO_SEARCH_ADV");
            echo "</a></p>
";
        }
        // line 13
        echo "
";
        // line 14
        if ((((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination")) || (isset($context["SEARCH_MATCHES"]) ? $context["SEARCH_MATCHES"] : null)) || (isset($context["TOTAL_MATCHES"]) ? $context["TOTAL_MATCHES"] : null)) || (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null))) {
            // line 15
            echo "\t<div class=\"action-bar top\">

\t";
            // line 17
            if (((isset($context["TOTAL_MATCHES"]) ? $context["TOTAL_MATCHES"] : null) > 0)) {
                // line 18
                echo "\t\t<div class=\"search-box\">
\t\t\t<form method=\"post\" action=\"";
                // line 19
                echo (isset($context["S_SEARCH_ACTION"]) ? $context["S_SEARCH_ACTION"] : null);
                echo "\">
\t\t\t<fieldset>
\t\t\t\t<input class=\"inputbox search tiny\" type=\"search\" name=\"add_keywords\" id=\"add_keywords\" value=\"\" placeholder=\"";
                // line 21
                echo $this->env->getExtension('phpbb')->lang("SEARCH_IN_RESULTS");
                echo "\" />
\t\t\t\t<button class=\"button icon-button search-icon\" type=\"submit\" title=\"";
                // line 22
                echo $this->env->getExtension('phpbb')->lang("SEARCH");
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("SEARCH");
                echo "</button>
\t\t\t\t<a href=\"";
                // line 23
                echo (isset($context["U_SEARCH"]) ? $context["U_SEARCH"] : null);
                echo "\" class=\"button icon-button search-adv-icon\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("SEARCH_ADV");
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("SEARCH_ADV");
                echo "</a>
\t\t\t</fieldset>
\t\t\t</form>
\t\t</div>
\t";
            }
            // line 28
            echo "
\t\t<div class=\"pagination\">
\t\t\t";
            // line 30
            echo (isset($context["SEARCH_MATCHES"]) ? $context["SEARCH_MATCHES"] : null);
            echo "
\t\t\t";
            // line 31
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                // line 32
                echo "\t\t\t\t";
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
                // line 33
                echo "\t\t\t";
            } else {
                // line 34
                echo "\t\t\t\t &bull; ";
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t";
            }
            // line 36
            echo "\t\t</div>
\t</div>
";
        }
        // line 39
        echo "
";
        // line 40
        if ((isset($context["S_SHOW_TOPICS"]) ? $context["S_SHOW_TOPICS"] : null)) {
            // line 41
            echo "
\t";
            // line 42
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "searchresults"))) {
                // line 43
                echo "\t<div class=\"forumbg\">

\t\t<div class=\"inner\">
\t\t<ul class=\"topiclist\">
\t\t\t<li class=\"header\">
\t\t\t\t<dl class=\"icon\">
\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 49
                echo $this->env->getExtension('phpbb')->lang("TOPICS");
                echo "</div></dt>
\t\t\t\t\t<dd class=\"posts\">";
                // line 50
                echo $this->env->getExtension('phpbb')->lang("REPLIES");
                echo "</dd>
\t\t\t\t\t<dd class=\"views\">";
                // line 51
                echo $this->env->getExtension('phpbb')->lang("VIEWS");
                echo "</dd>
\t\t\t\t\t<dd class=\"lastpost\"><span>";
                // line 52
                echo $this->env->getExtension('phpbb')->lang("LAST_POST");
                echo "</span></dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t</ul>
\t\t<ul class=\"topiclist topics\">

\t\t";
                // line 58
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "searchresults"));
                foreach ($context['_seq'] as $context["_key"] => $context["searchresults"]) {
                    // line 59
                    echo "\t\t\t";
                    // line 60
                    echo "\t\t\t<li class=\"row";
                    if (($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_ROW_COUNT") % 2 == 0)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t\t<dl class=\"icon ";
                    // line 61
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_IMG_STYLE");
                    echo "\">
\t\t\t\t\t<dt ";
                    // line 62
                    if ($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_ICON_IMG")) {
                        echo "style=\"background-image: url(";
                        echo (isset($context["T_ICONS_PATH"]) ? $context["T_ICONS_PATH"] : null);
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_ICON_IMG");
                        echo "); background-repeat: no-repeat;\"";
                    }
                    echo " title=\"";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_FOLDER_IMG_ALT");
                    echo "\">
\t\t\t\t\t\t";
                    // line 63
                    if (($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_UNREAD_TOPIC") && (!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)))) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_NEWEST_POST");
                        echo "\" class=\"icon-link\"></a>";
                    }
                    // line 64
                    echo "\t\t\t\t\t\t<div class=\"list-inner\">

\t\t\t\t\t\t\t";
                    // line 66
                    // line 67
                    echo "\t\t\t\t\t\t\t";
                    if (($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_UNREAD_TOPIC") && (!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)))) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_NEWEST_POST");
                        echo "\">";
                        echo (isset($context["NEWEST_POST_IMG"]) ? $context["NEWEST_POST_IMG"] : null);
                        echo "</a> ";
                    }
                    // line 68
                    echo "\t\t\t\t\t\t\t<a href=\"";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_VIEW_TOPIC");
                    echo "\" class=\"topictitle\">";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_TITLE");
                    echo "</a> ";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "ATTACH_ICON_IMG");
                    echo "
\t\t\t\t\t\t\t";
                    // line 69
                    if (($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_TOPIC_UNAPPROVED") || $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_POSTS_UNAPPROVED"))) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_MCP_QUEUE");
                        echo "\">";
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "UNAPPROVED_IMG");
                        echo "</a> ";
                    }
                    // line 70
                    echo "\t\t\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_TOPIC_DELETED")) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_MCP_QUEUE");
                        echo "\">";
                        echo (isset($context["DELETED_IMG"]) ? $context["DELETED_IMG"] : null);
                        echo "</a> ";
                    }
                    // line 71
                    echo "\t\t\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_TOPIC_REPORTED")) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_MCP_REPORT");
                        echo "\">";
                        echo (isset($context["REPORTED_IMG"]) ? $context["REPORTED_IMG"] : null);
                        echo "</a>";
                    }
                    echo "<br />
\t\t\t\t\t\t\t";
                    // line 72
                    if (twig_length_filter($this->env, $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "pagination"))) {
                        // line 73
                        echo "\t\t\t\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t\t\t\t<ul>
\t\t\t\t\t\t\t\t";
                        // line 75
                        $context['_parent'] = (array) $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "pagination"));
                        foreach ($context['_seq'] as $context["_key"] => $context["pagination"]) {
                            // line 76
                            echo "\t\t\t\t\t\t\t\t\t";
                            if ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_PREV")) {
                                // line 77
                                echo "\t\t\t\t\t\t\t\t\t";
                            } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_CURRENT")) {
                                echo "<li class=\"active\"><span>";
                                echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_NUMBER");
                                echo "</span></li>
\t\t\t\t\t\t\t\t\t";
                            } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_ELLIPSIS")) {
                                // line 78
                                echo "<li class=\"ellipsis\"><span>";
                                echo $this->env->getExtension('phpbb')->lang("ELLIPSIS");
                                echo "</span></li>
\t\t\t\t\t\t\t\t\t";
                            } elseif ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "S_IS_NEXT")) {
                                // line 80
                                echo "\t\t\t\t\t\t\t\t\t";
                            } else {
                                echo "<li><a href=\"";
                                echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_URL");
                                echo "\">";
                                echo $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "PAGE_NUMBER");
                                echo "</a></li>
\t\t\t\t\t\t\t\t\t";
                            }
                            // line 82
                            echo "\t\t\t\t\t\t\t\t";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pagination'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 83
                        echo "\t\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t";
                    }
                    // line 86
                    echo "\t\t\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_HAS_POLL")) {
                        echo (isset($context["POLL_IMG"]) ? $context["POLL_IMG"] : null);
                        echo " ";
                    }
                    // line 87
                    echo "\t\t\t\t\t\t\t";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_AUTHOR_FULL");
                    echo " &raquo; ";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "FIRST_POST_TIME");
                    echo " &raquo; ";
                    echo $this->env->getExtension('phpbb')->lang("IN");
                    echo " <a href=\"";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_VIEW_FORUM");
                    echo "\">";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "FORUM_TITLE");
                    echo "</a>
\t\t\t\t\t\t\t";
                    // line 88
                    // line 89
                    echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t<dd class=\"posts\">";
                    // line 92
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_REPLIES");
                    echo "</dd>
\t\t\t\t\t<dd class=\"views\">";
                    // line 93
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_VIEWS");
                    echo "</dd>
\t\t\t\t\t<dd class=\"lastpost\"><span>
\t\t\t\t\t\t";
                    // line 95
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "LAST_POST_AUTHOR_FULL");
                    echo "
\t\t\t\t\t\t";
                    // line 96
                    if ((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null))) {
                        echo "<a href=\"";
                        echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_LAST_POST");
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb')->lang("GOTO_LAST_POST");
                        echo "\">";
                        echo (isset($context["LAST_POST_IMG"]) ? $context["LAST_POST_IMG"] : null);
                        echo "</a> ";
                    }
                    echo "<br />";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "LAST_POST_TIME");
                    echo "<br /> </span>
\t\t\t\t\t</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t";
                    // line 100
                    // line 101
                    echo "\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['searchresults'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 102
                echo "\t\t</ul>

\t\t</div>
\t</div>
\t";
            } else {
                // line 107
                echo "\t\t<div class=\"panel\">
\t\t\t<div class=\"inner\">
\t\t\t<strong>";
                // line 109
                echo $this->env->getExtension('phpbb')->lang("NO_SEARCH_RESULTS");
                echo "</strong>
\t\t\t</div>
\t\t</div>
\t";
            }
            // line 113
            echo "
";
        } else {
            // line 115
            echo "
\t";
            // line 116
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "searchresults"));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["searchresults"]) {
                // line 117
                echo "\t\t";
                // line 118
                echo "\t\t<div class=\"search post ";
                if (($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_ROW_COUNT") % 2 == 1)) {
                    echo "bg1";
                } else {
                    echo "bg2";
                }
                if ($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_POST_REPORTED")) {
                    echo " reported";
                }
                echo "\">
\t\t\t<div class=\"inner\">

\t";
                // line 121
                if ($this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_IGNORE_POST")) {
                    // line 122
                    echo "\t\t<div class=\"postbody\"><div class=\"postbody-inner\">
\t\t\t";
                    // line 123
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "L_IGNORE_POST");
                    echo "
\t\t</div></div>
\t";
                } else {
                    // line 126
                    echo "\t\t<dl class=\"postprofile\">
\t\t\t";
                    // line 127
                    // line 128
                    echo "\t\t\t<dt class=\"author\">";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "POST_AUTHOR_FULL");
                    echo "</dt>
\t\t\t<dd class=\"search-result-date\">";
                    // line 129
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "POST_DATE");
                    echo "</dd>
\t\t\t<dd>";
                    // line 130
                    echo $this->env->getExtension('phpbb')->lang("FORUM");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <a href=\"";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_VIEW_FORUM");
                    echo "\">";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "FORUM_TITLE");
                    echo "</a></dd>
\t\t\t<dd>";
                    // line 131
                    echo $this->env->getExtension('phpbb')->lang("TOPIC");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <a href=\"";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_VIEW_TOPIC");
                    echo "\">";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_TITLE");
                    echo "</a></dd>
\t\t\t<dd>";
                    // line 132
                    echo $this->env->getExtension('phpbb')->lang("REPLIES");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <strong>";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_REPLIES");
                    echo "</strong></dd>
\t\t\t<dd>";
                    // line 133
                    echo $this->env->getExtension('phpbb')->lang("VIEWS");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <strong>";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "TOPIC_VIEWS");
                    echo "</strong></dd>
\t\t\t";
                    // line 134
                    // line 135
                    echo "\t\t</dl>

\t\t<div class=\"postbody\"><div class=\"postbody-inner\">
\t\t\t<h3><a href=\"";
                    // line 138
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_VIEW_POST");
                    echo "\">";
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "POST_SUBJECT");
                    echo "</a></h3>
\t\t\t<div class=\"content\">";
                    // line 139
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "MESSAGE");
                    echo "</div>
\t\t</div></div>
\t";
                }
                // line 142
                echo "
\t";
                // line 143
                if ((!$this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "S_IGNORE_POST"))) {
                    // line 144
                    echo "\t\t<ul class=\"searchresults\">
\t\t\t<li ><a href=\"";
                    // line 145
                    echo $this->getAttribute((isset($context["searchresults"]) ? $context["searchresults"] : null), "U_VIEW_POST");
                    echo "\" class=\"arrow-";
                    echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
                    echo "\">";
                    echo $this->env->getExtension('phpbb')->lang("JUMP_TO_POST");
                    echo "</a></li>
\t\t</ul>
\t";
                }
                // line 148
                echo "
\t\t\t</div>
\t\t</div>
\t\t";
                // line 151
                // line 152
                echo "\t";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 153
                echo "\t\t<div class=\"panel\">
\t\t\t<div class=\"inner\">
\t\t\t<strong>";
                // line 155
                echo $this->env->getExtension('phpbb')->lang("NO_SEARCH_RESULTS");
                echo "</strong>
\t\t\t</div>
\t\t</div>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['searchresults'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 160
        echo "
";
        // line 161
        if ((((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination")) || twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "searchresults"))) || (isset($context["S_SELECT_SORT_KEY"]) ? $context["S_SELECT_SORT_KEY"] : null)) || (isset($context["S_SELECT_SORT_DAYS"]) ? $context["S_SELECT_SORT_DAYS"] : null))) {
            // line 162
            echo "\t<form method=\"post\" action=\"";
            echo (isset($context["S_SEARCH_ACTION"]) ? $context["S_SEARCH_ACTION"] : null);
            echo "\">

\t<fieldset class=\"display-options\">
\t\t";
            // line 165
            if (((isset($context["S_SELECT_SORT_DAYS"]) ? $context["S_SELECT_SORT_DAYS"] : null) || (isset($context["S_SELECT_SORT_KEY"]) ? $context["S_SELECT_SORT_KEY"] : null))) {
                // line 166
                echo "\t\t\t<label>";
                if ((isset($context["S_SHOW_TOPICS"]) ? $context["S_SHOW_TOPICS"] : null)) {
                    echo $this->env->getExtension('phpbb')->lang("DISPLAY_POSTS");
                } else {
                    echo $this->env->getExtension('phpbb')->lang("SORT_BY");
                    echo "</label><label>";
                }
                echo " ";
                echo (isset($context["S_SELECT_SORT_DAYS"]) ? $context["S_SELECT_SORT_DAYS"] : null);
                if ((isset($context["S_SELECT_SORT_KEY"]) ? $context["S_SELECT_SORT_KEY"] : null)) {
                    echo "</label> <label>";
                    echo (isset($context["S_SELECT_SORT_KEY"]) ? $context["S_SELECT_SORT_KEY"] : null);
                    echo "</label>
\t\t\t<label>";
                    // line 167
                    echo (isset($context["S_SELECT_SORT_DIR"]) ? $context["S_SELECT_SORT_DIR"] : null);
                }
                echo "</label>
\t\t\t<input type=\"submit\" name=\"sort\" value=\"";
                // line 168
                echo $this->env->getExtension('phpbb')->lang("GO");
                echo "\" class=\"button2\" />
\t\t";
            }
            // line 170
            echo "\t</fieldset>

\t</form>

\t<hr />
";
        }
        // line 176
        echo "
";
        // line 177
        if (((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination")) || twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "searchresults"))) || (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null))) {
            // line 178
            echo "<div class=\"action-bar bottom\">
\t<div class=\"pagination\">
\t\t";
            // line 180
            echo (isset($context["SEARCH_MATCHES"]) ? $context["SEARCH_MATCHES"] : null);
            echo "
\t\t";
            // line 181
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                // line 182
                echo "\t\t\t";
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
                // line 183
                echo "\t\t";
            } else {
                // line 184
                echo "\t\t\t &bull; ";
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t";
            }
            // line 186
            echo "\t</div>
</div>
";
        }
        // line 189
        echo "
";
        // line 190
        $location = "jumpbox.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("jumpbox.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 191
        echo "
";
        // line 192
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
        return "search_results.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  692 => 192,  689 => 191,  677 => 190,  674 => 189,  669 => 186,  663 => 184,  660 => 183,  647 => 182,  645 => 181,  641 => 180,  637 => 178,  635 => 177,  632 => 176,  624 => 170,  619 => 168,  614 => 167,  599 => 166,  597 => 165,  590 => 162,  588 => 161,  585 => 160,  574 => 155,  570 => 153,  565 => 152,  564 => 151,  559 => 148,  549 => 145,  546 => 144,  544 => 143,  541 => 142,  535 => 139,  529 => 138,  524 => 135,  523 => 134,  516 => 133,  509 => 132,  500 => 131,  491 => 130,  487 => 129,  480 => 128,  479 => 127,  476 => 126,  470 => 123,  467 => 122,  465 => 121,  451 => 118,  449 => 117,  444 => 116,  441 => 115,  437 => 113,  430 => 109,  426 => 107,  419 => 102,  413 => 101,  412 => 100,  395 => 96,  389 => 95,  384 => 93,  380 => 92,  375 => 89,  374 => 88,  359 => 87,  353 => 86,  348 => 83,  342 => 82,  332 => 80,  326 => 78,  318 => 77,  315 => 76,  311 => 75,  307 => 73,  305 => 72,  294 => 71,  285 => 70,  277 => 69,  268 => 68,  259 => 67,  258 => 66,  254 => 64,  248 => 63,  237 => 62,  233 => 61,  224 => 60,  222 => 59,  218 => 58,  209 => 52,  205 => 51,  201 => 50,  197 => 49,  189 => 43,  187 => 42,  184 => 41,  182 => 40,  179 => 39,  174 => 36,  168 => 34,  165 => 33,  152 => 32,  150 => 31,  146 => 30,  142 => 28,  130 => 23,  124 => 22,  120 => 21,  115 => 19,  112 => 18,  110 => 17,  106 => 15,  104 => 14,  101 => 13,  89 => 11,  79 => 9,  77 => 8,  74 => 7,  68 => 6,  59 => 5,  50 => 4,  34 => 3,  31 => 2,  19 => 1,);
    }
}
