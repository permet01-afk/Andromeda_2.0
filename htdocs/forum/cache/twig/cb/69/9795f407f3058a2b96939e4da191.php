<?php

/* viewtopic_body.html */
class __TwigTemplate_cb699795f407f3058a2b96939e4da191 extends Twig_Template
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
        if (((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) && (((((((isset($context["U_VIEW_UNREAD_POST"]) ? $context["U_VIEW_UNREAD_POST"] : null) || (isset($context["U_WATCH_TOPIC"]) ? $context["U_WATCH_TOPIC"] : null)) || (isset($context["U_BOOKMARK_TOPIC"]) ? $context["U_BOOKMARK_TOPIC"] : null)) || (isset($context["U_BUMP_TOPIC"]) ? $context["U_BUMP_TOPIC"] : null)) || (isset($context["U_EMAIL_TOPIC"]) ? $context["U_EMAIL_TOPIC"] : null)) || (isset($context["U_PRINT_TOPIC"]) ? $context["U_PRINT_TOPIC"] : null)) || (isset($context["S_DISPLAY_TOPIC_TOOLS"]) ? $context["S_DISPLAY_TOPIC_TOOLS"] : null)))) {
            // line 3
            echo "\t";
            if ((isset($context["U_VIEW_UNREAD_POST"]) ? $context["U_VIEW_UNREAD_POST"] : null)) {
                // line 4
                echo "\t\t<li class=\"small-icon icon-mark\"><a href=\"";
                echo (isset($context["U_VIEW_UNREAD_POST"]) ? $context["U_VIEW_UNREAD_POST"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("VIEW_UNREAD_POST");
                echo "</a></li>
\t";
            }
            // line 6
            echo "\t";
            if ((isset($context["U_WATCH_TOPIC"]) ? $context["U_WATCH_TOPIC"] : null)) {
                // line 7
                echo "\t\t<li class=\"small-icon icon-";
                if ((isset($context["S_WATCHING_TOPIC"]) ? $context["S_WATCHING_TOPIC"] : null)) {
                    echo "unsubscribe";
                } else {
                    echo "subscribe";
                }
                echo "\">
\t\t\t<a href=\"";
                // line 8
                echo (isset($context["U_WATCH_TOPIC"]) ? $context["U_WATCH_TOPIC"] : null);
                echo "\" class=\"watch-topic-link\" title=\"";
                echo (isset($context["S_WATCH_TOPIC_TITLE"]) ? $context["S_WATCH_TOPIC_TITLE"] : null);
                echo "\" data-ajax=\"toggle_link\" data-toggle-class=\"small-icon icon-";
                if ((!(isset($context["S_WATCHING_TOPIC"]) ? $context["S_WATCHING_TOPIC"] : null))) {
                    echo "unsubscribe";
                } else {
                    echo "subscribe";
                }
                echo "\" data-toggle-text=\"";
                echo (isset($context["S_WATCH_TOPIC_TOGGLE"]) ? $context["S_WATCH_TOPIC_TOGGLE"] : null);
                echo "\" data-toggle-url=\"";
                echo (isset($context["U_WATCH_TOPIC_TOGGLE"]) ? $context["U_WATCH_TOPIC_TOGGLE"] : null);
                echo "\" data-update-all=\".watch-topic-link\">";
                echo (isset($context["S_WATCH_TOPIC_TITLE"]) ? $context["S_WATCH_TOPIC_TITLE"] : null);
                echo "</a>
\t\t</li>
\t";
            }
            // line 11
            $value = 1;
            $context['definition']->set('NAVLINKS_SHOW_DEFAULT', $value);
        }
        $value = ('' === $value = ob_get_clean()) ? '' : new \Twig_Markup($value, $this->env->getCharset());
        $context['definition']->set('NAVLINKS', $value);
        // line 14
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
        // line 15
        echo "
<h2 class=\"topic-title\">";
        // line 16
        echo "<a href=\"";
        echo (isset($context["U_VIEW_TOPIC"]) ? $context["U_VIEW_TOPIC"] : null);
        echo "\">";
        echo (isset($context["TOPIC_TITLE"]) ? $context["TOPIC_TITLE"] : null);
        echo "</a>";
        echo "</h2>
<!-- NOTE: remove the style=\"display: none\" when you want to have the forum description on the topic body -->
";
        // line 18
        if ((isset($context["FORUM_DESC"]) ? $context["FORUM_DESC"] : null)) {
            echo "<div style=\"display: none !important;\">";
            echo (isset($context["FORUM_DESC"]) ? $context["FORUM_DESC"] : null);
            echo "<br /></div>";
        }
        // line 19
        echo "
";
        // line 20
        if ((isset($context["MODERATORS"]) ? $context["MODERATORS"] : null)) {
            // line 21
            echo "<p>
\t<strong>";
            // line 22
            if ((isset($context["S_SINGLE_MODERATOR"]) ? $context["S_SINGLE_MODERATOR"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("MODERATOR");
            } else {
                echo $this->env->getExtension('phpbb')->lang("MODERATORS");
            }
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</strong> ";
            echo (isset($context["MODERATORS"]) ? $context["MODERATORS"] : null);
            echo "
</p>
";
        }
        // line 25
        echo "
";
        // line 26
        if ((isset($context["S_FORUM_RULES"]) ? $context["S_FORUM_RULES"] : null)) {
            // line 27
            echo "\t<div class=\"rules";
            if ((isset($context["U_FORUM_RULES"]) ? $context["U_FORUM_RULES"] : null)) {
                echo " rules-link";
            }
            echo "\">
\t\t<div class=\"inner\">

\t\t";
            // line 30
            if ((isset($context["U_FORUM_RULES"]) ? $context["U_FORUM_RULES"] : null)) {
                // line 31
                echo "\t\t\t<a href=\"";
                echo (isset($context["U_FORUM_RULES"]) ? $context["U_FORUM_RULES"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("FORUM_RULES");
                echo "</a>
\t\t";
            } else {
                // line 33
                echo "\t\t\t<strong>";
                echo $this->env->getExtension('phpbb')->lang("FORUM_RULES");
                echo "</strong><br />
\t\t\t";
                // line 34
                echo (isset($context["FORUM_RULES"]) ? $context["FORUM_RULES"] : null);
                echo "
\t\t";
            }
            // line 36
            echo "
\t\t</div>
\t</div>
";
        }
        // line 40
        echo "
<div class=\"action-bar top\">

\t<div class=\"buttons\">
\t\t";
        // line 44
        // line 45
        echo "
\t";
        // line 46
        if (((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) && (isset($context["S_DISPLAY_REPLY_INFO"]) ? $context["S_DISPLAY_REPLY_INFO"] : null))) {
            // line 47
            echo "\t\t<a href=\"";
            echo (isset($context["U_POST_REPLY_TOPIC"]) ? $context["U_POST_REPLY_TOPIC"] : null);
            echo "\" class=\"button icon-button ";
            if ((isset($context["S_IS_LOCKED"]) ? $context["S_IS_LOCKED"] : null)) {
                echo "locked-icon";
            } else {
                echo "reply-icon";
            }
            echo "\" title=\"";
            if ((isset($context["S_IS_LOCKED"]) ? $context["S_IS_LOCKED"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("TOPIC_LOCKED");
            } else {
                echo $this->env->getExtension('phpbb')->lang("POST_REPLY");
            }
            echo "\">
\t\t\t";
            // line 48
            if ((isset($context["S_IS_LOCKED"]) ? $context["S_IS_LOCKED"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("BUTTON_TOPIC_LOCKED");
            } else {
                echo $this->env->getExtension('phpbb')->lang("BUTTON_POST_REPLY");
            }
            // line 49
            echo "\t\t</a>
\t";
        }
        // line 51
        echo "
\t\t";
        // line 52
        // line 53
        echo "\t</div>

\t";
        // line 55
        $location = "viewtopic_topic_tools.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("viewtopic_topic_tools.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 56
        echo "
\t";
        // line 57
        if ((isset($context["S_DISPLAY_SEARCHBOX"]) ? $context["S_DISPLAY_SEARCHBOX"] : null)) {
            // line 58
            echo "\t\t<div class=\"search-box\">
\t\t\t<form method=\"get\" id=\"topic-search\" action=\"";
            // line 59
            echo (isset($context["S_SEARCHBOX_ACTION"]) ? $context["S_SEARCHBOX_ACTION"] : null);
            echo "\">
\t\t\t<fieldset>
\t\t\t\t<input class=\"inputbox search tiny\"  type=\"search\" name=\"keywords\" id=\"search_keywords\" size=\"20\" placeholder=\"";
            // line 61
            echo $this->env->getExtension('phpbb')->lang("SEARCH_TOPIC");
            echo "\" />
\t\t\t\t<button class=\"button icon-button search-icon\" type=\"submit\" title=\"";
            // line 62
            echo $this->env->getExtension('phpbb')->lang("SEARCH");
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("SEARCH");
            echo "</button>
\t\t\t\t<a href=\"";
            // line 63
            echo (isset($context["U_SEARCH"]) ? $context["U_SEARCH"] : null);
            echo "\" class=\"button icon-button search-adv-icon\" title=\"";
            echo $this->env->getExtension('phpbb')->lang("SEARCH_ADV");
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("SEARCH_ADV");
            echo "</a>
\t\t\t\t";
            // line 64
            echo (isset($context["S_SEARCH_LOCAL_HIDDEN_FIELDS"]) ? $context["S_SEARCH_LOCAL_HIDDEN_FIELDS"] : null);
            echo "
\t\t\t</fieldset>
\t\t\t</form>
\t\t</div>
\t";
        }
        // line 69
        echo "
\t";
        // line 70
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination")) || (isset($context["TOTAL_POSTS"]) ? $context["TOTAL_POSTS"] : null))) {
            // line 71
            echo "\t\t<div class=\"pagination\">
\t\t\t";
            // line 72
            if (((isset($context["U_VIEW_UNREAD_POST"]) ? $context["U_VIEW_UNREAD_POST"] : null) && (!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)))) {
                echo "<a href=\"";
                echo (isset($context["U_VIEW_UNREAD_POST"]) ? $context["U_VIEW_UNREAD_POST"] : null);
                echo "\" class=\"mark\">";
                echo $this->env->getExtension('phpbb')->lang("VIEW_UNREAD_POST");
                echo "</a> &bull; ";
            }
            echo (isset($context["TOTAL_POSTS"]) ? $context["TOTAL_POSTS"] : null);
            echo "
\t\t\t";
            // line 73
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                // line 74
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
                // line 75
                echo "\t\t\t";
            } else {
                // line 76
                echo "\t\t\t\t&bull; ";
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t";
            }
            // line 78
            echo "\t\t</div>
\t";
        }
        // line 80
        echo "
</div>

";
        // line 83
        if ((isset($context["S_HAS_POLL"]) ? $context["S_HAS_POLL"] : null)) {
            // line 84
            echo "\t<form method=\"post\" action=\"";
            echo (isset($context["S_POLL_ACTION"]) ? $context["S_POLL_ACTION"] : null);
            echo "\" data-ajax=\"vote_poll\" class=\"topic_poll\">

\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<div class=\"content\">
\t\t\t<h2 class=\"poll-title\">";
            // line 90
            echo (isset($context["POLL_QUESTION"]) ? $context["POLL_QUESTION"] : null);
            echo "</h2>
\t\t\t<p class=\"author\">";
            // line 91
            echo $this->env->getExtension('phpbb')->lang("POLL_LENGTH");
            if (((isset($context["S_CAN_VOTE"]) ? $context["S_CAN_VOTE"] : null) && (isset($context["L_POLL_LENGTH"]) ? $context["L_POLL_LENGTH"] : null))) {
                echo "<br />";
            }
            if ((isset($context["S_CAN_VOTE"]) ? $context["S_CAN_VOTE"] : null)) {
                echo "<span class=\"poll_max_votes\">";
                echo $this->env->getExtension('phpbb')->lang("MAX_VOTES");
                echo "</span>";
            }
            echo "</p>

\t\t\t<fieldset class=\"polls\">
\t\t\t";
            // line 94
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "poll_option"));
            foreach ($context['_seq'] as $context["_key"] => $context["poll_option"]) {
                // line 95
                echo "\t\t\t\t";
                // line 96
                echo "\t\t\t\t<dl class=\"";
                if ($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_VOTED")) {
                    echo "voted";
                }
                if ($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_MOST_VOTES")) {
                    echo " most-votes";
                }
                echo "\"";
                if ($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_VOTED")) {
                    echo " title=\"";
                    echo $this->env->getExtension('phpbb')->lang("POLL_VOTED_OPTION");
                    echo "\"";
                }
                echo " data-poll-option-id=\"";
                echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_ID");
                echo "\">
\t\t\t\t\t<dt>";
                // line 97
                if ((isset($context["S_CAN_VOTE"]) ? $context["S_CAN_VOTE"] : null)) {
                    echo "<label for=\"vote_";
                    echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_ID");
                    echo "\">";
                    echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_CAPTION");
                    echo "</label>";
                } else {
                    echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_CAPTION");
                }
                echo "</dt>
\t\t\t\t\t";
                // line 98
                if ((isset($context["S_CAN_VOTE"]) ? $context["S_CAN_VOTE"] : null)) {
                    echo "<dd style=\"width: auto;\" class=\"poll_option_select\">";
                    if ((isset($context["S_IS_MULTI_CHOICE"]) ? $context["S_IS_MULTI_CHOICE"] : null)) {
                        echo "<input type=\"checkbox\" name=\"vote_id[]\" id=\"vote_";
                        echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_ID");
                        echo "\" value=\"";
                        echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_ID");
                        echo "\"";
                        if ($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_VOTED")) {
                            echo " checked=\"checked\"";
                        }
                        echo " />";
                    } else {
                        echo "<input type=\"radio\" name=\"vote_id[]\" id=\"vote_";
                        echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_ID");
                        echo "\" value=\"";
                        echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_ID");
                        echo "\"";
                        if ($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_VOTED")) {
                            echo " checked=\"checked\"";
                        }
                        echo " />";
                    }
                    echo "</dd>";
                }
                // line 99
                echo "\t\t\t\t\t<dd class=\"resultbar";
                if ((!(isset($context["S_DISPLAY_RESULTS"]) ? $context["S_DISPLAY_RESULTS"] : null))) {
                    echo " hidden";
                }
                echo "\"><div class=\"";
                if (($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_PCT") < 20)) {
                    echo "pollbar1";
                } elseif (($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_PCT") < 40)) {
                    echo "pollbar2";
                } elseif (($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_PCT") < 60)) {
                    echo "pollbar3";
                } elseif (($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_PCT") < 80)) {
                    echo "pollbar4";
                } else {
                    echo "pollbar5";
                }
                echo "\" style=\"width:";
                echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_PERCENT_REL");
                echo ";\">";
                echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_RESULT");
                echo "</div></dd>
\t\t\t\t\t<dd class=\"poll_option_percent";
                // line 100
                if ((!(isset($context["S_DISPLAY_RESULTS"]) ? $context["S_DISPLAY_RESULTS"] : null))) {
                    echo " hidden";
                }
                echo "\">";
                if (($this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_RESULT") == 0)) {
                    echo $this->env->getExtension('phpbb')->lang("NO_VOTES");
                } else {
                    echo $this->getAttribute((isset($context["poll_option"]) ? $context["poll_option"] : null), "POLL_OPTION_PERCENT");
                }
                echo "</dd>
\t\t\t\t</dl>
\t\t\t\t";
                // line 102
                // line 103
                echo "\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['poll_option'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 104
            echo "
\t\t\t\t<dl class=\"poll_total_votes";
            // line 105
            if ((!(isset($context["S_DISPLAY_RESULTS"]) ? $context["S_DISPLAY_RESULTS"] : null))) {
                echo " hidden";
            }
            echo "\">
\t\t\t\t\t<dt>&nbsp;</dt>
\t\t\t\t\t<dd class=\"resultbar\">";
            // line 107
            echo $this->env->getExtension('phpbb')->lang("TOTAL_VOTES");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " <span class=\"poll_total_vote_cnt\">";
            echo (isset($context["TOTAL_VOTES"]) ? $context["TOTAL_VOTES"] : null);
            echo "</span></dd>
\t\t\t\t</dl>

\t\t\t";
            // line 110
            if ((isset($context["S_CAN_VOTE"]) ? $context["S_CAN_VOTE"] : null)) {
                // line 111
                echo "\t\t\t\t<dl style=\"border-top: none;\" class=\"poll_vote\">
\t\t\t\t\t<dt>&nbsp;</dt>
\t\t\t\t\t<dd class=\"resultbar\"><input type=\"submit\" name=\"update\" value=\"";
                // line 113
                echo $this->env->getExtension('phpbb')->lang("SUBMIT_VOTE");
                echo "\" class=\"button1\" /></dd>
\t\t\t\t</dl>
\t\t\t";
            }
            // line 116
            echo "
\t\t\t";
            // line 117
            if ((!(isset($context["S_DISPLAY_RESULTS"]) ? $context["S_DISPLAY_RESULTS"] : null))) {
                // line 118
                echo "\t\t\t\t<dl style=\"border-top: none;\" class=\"poll_view_results\">
\t\t\t\t\t<dt>&nbsp;</dt>
\t\t\t\t\t<dd class=\"resultbar\"><a href=\"";
                // line 120
                echo (isset($context["U_VIEW_RESULTS"]) ? $context["U_VIEW_RESULTS"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("VIEW_RESULTS");
                echo "</a></dd>
\t\t\t\t</dl>
\t\t\t";
            }
            // line 123
            echo "\t\t\t</fieldset>
\t\t\t<div class=\"vote-submitted hidden\">";
            // line 124
            echo $this->env->getExtension('phpbb')->lang("VOTE_SUBMITTED");
            echo "</div>
\t\t</div>

\t\t</div>
\t\t";
            // line 128
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t\t";
            // line 129
            echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
            echo "
\t</div>

\t</form>
";
        }
        // line 134
        echo "
";
        // line 135
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "postrow"));
        foreach ($context['_seq'] as $context["_key"] => $context["postrow"]) {
            // line 136
            echo "\t";
            // line 137
            echo "\t";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_FIRST_UNREAD")) {
                // line 138
                echo "\t\t<a id=\"unread\" class=\"anchor\"";
                if ((isset($context["S_UNREAD_VIEW"]) ? $context["S_UNREAD_VIEW"] : null)) {
                    echo " data-url=\"";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_MINI_POST");
                    echo "\"";
                }
                echo "></a>
\t";
            }
            // line 140
            echo "\t<div id=\"p";
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
            echo "\" class=\"post has-profile ";
            if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_ROW_COUNT") % 2 == 1)) {
                echo "bg1";
            } else {
                echo "bg2";
            }
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_UNREAD_POST")) {
                echo " unreadpost";
            }
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_REPORTED")) {
                echo " reported";
            }
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_DELETED")) {
                echo " deleted";
            }
            if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_ONLINE") && (!$this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_HIDDEN")))) {
                echo " online";
            }
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_WARNINGS")) {
                echo " warned";
            }
            echo "\">
\t\t<div class=\"inner\">

\t\t<dl class=\"postprofile\" id=\"profile";
            // line 143
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
            echo "\"";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_HIDDEN")) {
                echo " style=\"display: none;\"";
            }
            echo ">
\t\t\t<dt class=\"";
            // line 144
            if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_TITLE") || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_IMG"))) {
                echo "has-profile-rank";
            } else {
                echo "no-profile-rank";
            }
            echo " ";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_AVATAR")) {
                echo "has-avatar";
            } else {
                echo "no-avatar";
            }
            echo "\">
\t\t\t\t<div class=\"avatar-container\">
\t\t\t\t\t";
            // line 146
            // line 147
            echo "\t\t\t\t\t";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_AVATAR")) {
                // line 148
                echo "\t\t\t\t\t\t";
                if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_POST_AUTHOR")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_POST_AUTHOR");
                    echo "\" class=\"avatar\">";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_AVATAR");
                    echo "</a>";
                } else {
                    echo "<span class=\"avatar\">";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_AVATAR");
                    echo "</span>";
                }
                // line 149
                echo "\t\t\t\t\t";
            }
            // line 150
            echo "\t\t\t\t\t";
            // line 151
            echo "\t\t\t\t</div>
\t\t\t\t";
            // line 152
            // line 153
            echo "\t\t\t\t";
            if ((!$this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_POST_AUTHOR"))) {
                echo "<strong>";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_AUTHOR_FULL");
                echo "</strong>";
            } else {
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_AUTHOR_FULL");
            }
            // line 154
            echo "\t\t\t\t";
            // line 155
            echo "\t\t\t</dt>

\t\t\t";
            // line 157
            if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_TITLE") || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_IMG"))) {
                echo "<dd class=\"profile-rank\">";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_TITLE");
                if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_TITLE") && $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_IMG"))) {
                    echo "<br />";
                }
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "RANK_IMG");
                echo "</dd>";
            }
            // line 158
            echo "
\t\t";
            // line 159
            if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_POSTS") != "")) {
                echo "<dd class=\"profile-posts\"><strong>";
                echo $this->env->getExtension('phpbb')->lang("POSTS");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</strong> ";
                if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_SEARCH") !== "")) {
                    echo "<a href=\"";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_SEARCH");
                    echo "\">";
                }
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_POSTS");
                if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_SEARCH") !== "")) {
                    echo "</a>";
                }
                echo "</dd>";
            }
            // line 160
            echo "\t\t";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_JOINED")) {
                echo "<dd class=\"profile-joined\"><strong>";
                echo $this->env->getExtension('phpbb')->lang("JOINED");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</strong> ";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_JOINED");
                echo "</dd>";
            }
            // line 161
            echo "\t\t";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_WARNINGS")) {
                echo "<dd class=\"profile-warnings\"><strong>";
                echo $this->env->getExtension('phpbb')->lang("WARNINGS");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</strong> ";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POSTER_WARNINGS");
                echo "</dd>";
            }
            // line 162
            echo "
\t\t";
            // line 163
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_PROFILE_FIELD1")) {
                // line 164
                echo "\t\t\t<!-- Use a construct like this to include admin defined profile fields. Replace FIELD1 with the name of your field. -->
\t\t\t<dd><strong>";
                // line 165
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "PROFILE_FIELD1_NAME");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</strong> ";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "PROFILE_FIELD1_VALUE");
                echo "</dd>
\t\t";
            }
            // line 167
            echo "
\t\t";
            // line 168
            // line 169
            echo "\t\t";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "custom_fields"));
            foreach ($context['_seq'] as $context["_key"] => $context["custom_fields"]) {
                // line 170
                echo "\t\t\t";
                if ((!$this->getAttribute((isset($context["custom_fields"]) ? $context["custom_fields"] : null), "S_PROFILE_CONTACT"))) {
                    // line 171
                    echo "\t\t\t\t<dd class=\"profile-custom-field profile-";
                    echo $this->getAttribute((isset($context["custom_fields"]) ? $context["custom_fields"] : null), "PROFILE_FIELD_IDENT");
                    echo "\"><strong>";
                    echo $this->getAttribute((isset($context["custom_fields"]) ? $context["custom_fields"] : null), "PROFILE_FIELD_NAME");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</strong> ";
                    echo $this->getAttribute((isset($context["custom_fields"]) ? $context["custom_fields"] : null), "PROFILE_FIELD_VALUE");
                    echo "</dd>
\t\t\t";
                }
                // line 173
                echo "\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['custom_fields'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 174
            echo "\t\t";
            // line 175
            echo "
\t\t";
            // line 176
            // line 177
            echo "\t\t";
            if (((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) && twig_length_filter($this->env, $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "contact")))) {
                // line 178
                echo "\t\t\t<dd class=\"profile-contact\">
\t\t\t\t<strong>";
                // line 179
                echo $this->env->getExtension('phpbb')->lang("CONTACT");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</strong>
\t\t\t\t<div class=\"dropdown-container dropdown-";
                // line 180
                echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
                echo " dropdown-down\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-trigger\"><span class=\"imageset icon_contact\" title=\"";
                // line 181
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "CONTACT_USER");
                echo "\">";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "CONTACT_USER");
                echo "</span></a>
\t\t\t\t\t<div class=\"dropdown hidden\">
\t\t\t\t\t\t<div class=\"pointer\"><div class=\"pointer-inner\"></div></div>
\t\t\t\t\t\t<div class=\"dropdown-contents contact-icons\">
\t\t\t\t\t\t\t";
                // line 185
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "contact"));
                foreach ($context['_seq'] as $context["_key"] => $context["contact"]) {
                    // line 186
                    echo "\t\t\t\t\t\t\t\t";
                    $context["REMAINDER"] = ($this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "S_ROW_COUNT") % 4);
                    // line 187
                    echo "\t\t\t\t\t\t\t\t";
                    $value = (((isset($context["REMAINDER"]) ? $context["REMAINDER"] : null) == 3) || ($this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "S_LAST_ROW") && ($this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "S_NUM_ROWS") < 4)));
                    $context['definition']->set('S_LAST_CELL', $value);
                    // line 188
                    echo "\t\t\t\t\t\t\t\t";
                    if (((isset($context["REMAINDER"]) ? $context["REMAINDER"] : null) == 0)) {
                        // line 189
                        echo "\t\t\t\t\t\t\t\t\t<div>
\t\t\t\t\t\t\t\t";
                    }
                    // line 191
                    echo "\t\t\t\t\t\t\t\t\t<a href=\"";
                    if ($this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "U_CONTACT")) {
                        echo $this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "U_CONTACT");
                    } else {
                        echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_POST_AUTHOR");
                    }
                    echo "\" title=\"";
                    echo $this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "NAME");
                    echo "\"";
                    if ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "S_LAST_CELL")) {
                        echo " class=\"last-cell\"";
                    }
                    if (($this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "ID") == "jabber")) {
                        echo " onclick=\"popup(this.href, 750, 320); return false;\"";
                    }
                    echo ">
\t\t\t\t\t\t\t\t\t\t<span class=\"contact-icon ";
                    // line 192
                    echo $this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "ID");
                    echo "-icon\">";
                    echo $this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "NAME");
                    echo "</span>
\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t";
                    // line 194
                    if ((((isset($context["REMAINDER"]) ? $context["REMAINDER"] : null) == 3) || $this->getAttribute((isset($context["contact"]) ? $context["contact"] : null), "S_LAST_ROW"))) {
                        // line 195
                        echo "\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t";
                    }
                    // line 197
                    echo "\t\t\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['contact'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 198
                echo "\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</dd>
\t\t";
            }
            // line 203
            echo "\t\t";
            // line 204
            echo "
\t\t</dl>

\t\t<div class=\"postbody\">

\t\t\t";
            // line 209
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_HIDDEN")) {
                // line 210
                echo "\t\t\t\t";
                if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_DELETED")) {
                    // line 211
                    echo "\t\t\t\t\t<div class=\"ignore\" id=\"post_hidden";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
                    echo "\">
\t\t\t\t\t\t";
                    // line 212
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "L_POST_DELETED_MESSAGE");
                    echo "<br />
\t\t\t\t\t\t";
                    // line 213
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "L_POST_DISPLAY");
                    echo "
\t\t\t\t\t</div>
\t\t\t\t";
                } elseif ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_IGNORE_POST")) {
                    // line 216
                    echo "\t\t\t\t\t<div class=\"ignore\" id=\"post_hidden";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
                    echo "\">
\t\t\t\t\t\t";
                    // line 217
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "L_IGNORE_POST");
                    echo "<br />
\t\t\t\t\t\t";
                    // line 218
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "L_POST_DISPLAY");
                    echo "
\t\t\t\t\t</div>
\t\t\t\t";
                }
                // line 221
                echo "\t\t\t";
            }
            // line 222
            echo "\t\t\t<div id=\"post_content";
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
            echo "\"";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_HIDDEN")) {
                echo " style=\"display: none;\"";
            }
            echo ">

\t\t\t<h3 ";
            // line 224
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_FIRST_ROW")) {
                echo "class=\"first\"";
            }
            echo ">";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ICON_IMG")) {
                echo "<img src=\"";
                echo (isset($context["T_ICONS_PATH"]) ? $context["T_ICONS_PATH"] : null);
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ICON_IMG");
                echo "\" width=\"";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ICON_IMG_WIDTH");
                echo "\" height=\"";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ICON_IMG_HEIGHT");
                echo "\" alt=\"\" /> ";
            }
            echo "<a href=\"#p";
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
            echo "\">";
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_SUBJECT");
            echo "</a></h3>

\t\t";
            // line 226
            if ((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null))) {
                // line 227
                echo "\t\t\t";
                if (((((($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_EDIT") || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_DELETE")) || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_REPORT")) || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_WARN")) || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_INFO")) || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_QUOTE"))) {
                    // line 228
                    echo "\t\t\t\t<ul class=\"post-buttons\">
\t\t\t\t\t";
                    // line 229
                    // line 230
                    echo "\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_EDIT")) {
                        // line 231
                        echo "\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t<a href=\"";
                        // line 232
                        echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_EDIT");
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb')->lang("EDIT_POST");
                        echo "\" class=\"button icon-button edit-icon\"><span>";
                        echo $this->env->getExtension('phpbb')->lang("BUTTON_EDIT");
                        echo "</span></a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                    }
                    // line 235
                    echo "\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_DELETE")) {
                        // line 236
                        echo "\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t<a href=\"";
                        // line 237
                        echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_DELETE");
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb')->lang("DELETE_POST");
                        echo "\" class=\"button icon-button delete-icon\"><span>";
                        echo $this->env->getExtension('phpbb')->lang("DELETE_POST");
                        echo "</span></a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                    }
                    // line 240
                    echo "\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_REPORT")) {
                        // line 241
                        echo "\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t<a href=\"";
                        // line 242
                        echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_REPORT");
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb')->lang("REPORT_POST");
                        echo "\" class=\"button icon-button report-icon\"><span>";
                        echo $this->env->getExtension('phpbb')->lang("REPORT_POST");
                        echo "</span></a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                    }
                    // line 245
                    echo "\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_WARN")) {
                        // line 246
                        echo "\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t<a href=\"";
                        // line 247
                        echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_WARN");
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb')->lang("WARN_USER");
                        echo "\" class=\"button icon-button warn-icon\"><span>";
                        echo $this->env->getExtension('phpbb')->lang("WARN_USER");
                        echo "</span></a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                    }
                    // line 250
                    echo "\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_INFO")) {
                        // line 251
                        echo "\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t<a href=\"";
                        // line 252
                        echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_INFO");
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb')->lang("INFORMATION");
                        echo "\" class=\"button icon-button info-icon\"><span>";
                        echo $this->env->getExtension('phpbb')->lang("INFORMATION");
                        echo "</span></a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                    }
                    // line 255
                    echo "\t\t\t\t\t";
                    if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_QUOTE")) {
                        // line 256
                        echo "\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t<a href=\"";
                        // line 257
                        echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_QUOTE");
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb')->lang("REPLY_WITH_QUOTE");
                        echo "\" class=\"button icon-button quote-icon\"><span>";
                        echo $this->env->getExtension('phpbb')->lang("QUOTE");
                        echo "</span></a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                    }
                    // line 260
                    echo "\t\t\t\t\t";
                    // line 261
                    echo "\t\t\t\t</ul>
\t\t\t";
                }
                // line 263
                echo "\t\t";
            }
            // line 264
            echo "
\t\t\t<p class=\"author\">";
            // line 265
            if ((isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) {
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "MINI_POST_IMG");
            } else {
                echo "<a href=\"";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_MINI_POST");
                echo "\">";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "MINI_POST_IMG");
                echo "</a>";
            }
            echo "<span class=\"responsive-hide\">";
            echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
            echo " <strong>";
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_AUTHOR_FULL");
            echo "</strong> &raquo; </span>";
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_DATE");
            echo " </p>

\t\t\t";
            // line 267
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_UNAPPROVED")) {
                // line 268
                echo "\t\t\t<form method=\"post\" class=\"mcp_approve\" action=\"";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_APPROVE_ACTION");
                echo "\">
\t\t\t\t<p class=\"post-notice unapproved\">
\t\t\t\t\t<strong>";
                // line 270
                echo $this->env->getExtension('phpbb')->lang("POST_UNAPPROVED_ACTION");
                echo "</strong>
\t\t\t\t\t<input class=\"button2\" type=\"submit\" value=\"";
                // line 271
                echo $this->env->getExtension('phpbb')->lang("DISAPPROVE");
                echo "\" name=\"action[disapprove]\" />
\t\t\t\t\t<input class=\"button1\" type=\"submit\" value=\"";
                // line 272
                echo $this->env->getExtension('phpbb')->lang("APPROVE");
                echo "\" name=\"action[approve]\" />
\t\t\t\t\t<input type=\"hidden\" name=\"post_id_list[]\" value=\"";
                // line 273
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
                echo "\" />
\t\t\t\t\t";
                // line 274
                echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
                echo "
\t\t\t\t</p>
\t\t\t</form>
\t\t\t";
            } elseif ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_DELETED")) {
                // line 278
                echo "\t\t\t<form method=\"post\" class=\"mcp_approve\" action=\"";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_APPROVE_ACTION");
                echo "\">
\t\t\t\t<p class=\"post-notice deleted\">
\t\t\t\t\t<strong>";
                // line 280
                echo $this->env->getExtension('phpbb')->lang("POST_DELETED_ACTION");
                echo "</strong>
\t\t\t\t\t<input class=\"button2\" type=\"submit\" value=\"";
                // line 281
                echo $this->env->getExtension('phpbb')->lang("DELETE");
                echo "\" name=\"action[disapprove]\" />
\t\t\t\t\t<input class=\"button1\" type=\"submit\" value=\"";
                // line 282
                echo $this->env->getExtension('phpbb')->lang("RESTORE");
                echo "\" name=\"action[restore]\" />
\t\t\t\t\t<input type=\"hidden\" name=\"post_id_list[]\" value=\"";
                // line 283
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
                echo "\" />
\t\t\t\t\t";
                // line 284
                echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
                echo "
\t\t\t\t</p>
\t\t\t</form>
\t\t\t";
            }
            // line 288
            echo "
\t\t\t";
            // line 289
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_POST_REPORTED")) {
                // line 290
                echo "\t\t\t<p class=\"post-notice reported\">
\t\t\t\t<a href=\"";
                // line 291
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "U_MCP_REPORT");
                echo "\"><strong>";
                echo $this->env->getExtension('phpbb')->lang("POST_REPORTED");
                echo "</strong></a>
\t\t\t</p>
\t\t\t";
            }
            // line 294
            echo "
\t\t\t<div class=\"content\">";
            // line 295
            echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "MESSAGE");
            echo "</div>

\t\t\t";
            // line 297
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_HAS_ATTACHMENTS")) {
                // line 298
                echo "\t\t\t\t<dl class=\"attachbox\">
\t\t\t\t\t<dt>
\t\t\t\t\t\t";
                // line 300
                echo $this->env->getExtension('phpbb')->lang("ATTACHMENTS");
                echo "
\t\t\t\t\t</dt>
\t\t\t\t\t";
                // line 302
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "attachment"));
                foreach ($context['_seq'] as $context["_key"] => $context["attachment"]) {
                    // line 303
                    echo "\t\t\t\t\t\t<dd>";
                    echo $this->getAttribute((isset($context["attachment"]) ? $context["attachment"] : null), "DISPLAY_ATTACHMENT");
                    echo "</dd>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attachment'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 305
                echo "\t\t\t\t</dl>
\t\t\t";
            }
            // line 307
            echo "
\t\t\t";
            // line 308
            // line 309
            echo "\t\t\t";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "S_DISPLAY_NOTICE")) {
                echo "<div class=\"rules\">";
                echo $this->env->getExtension('phpbb')->lang("DOWNLOAD_NOTICE");
                echo "</div>";
            }
            // line 310
            echo "\t\t\t";
            if (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "DELETED_MESSAGE") || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "DELETE_REASON"))) {
                // line 311
                echo "\t\t\t\t<div class=\"notice post_deleted_msg\">
\t\t\t\t\t";
                // line 312
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "DELETED_MESSAGE");
                echo "
\t\t\t\t\t";
                // line 313
                if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "DELETE_REASON")) {
                    echo "<br /><strong>";
                    echo $this->env->getExtension('phpbb')->lang("REASON");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</strong> <em>";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "DELETE_REASON");
                    echo "</em>";
                }
                // line 314
                echo "\t\t\t\t</div>
\t\t\t";
            } elseif (($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "EDITED_MESSAGE") || $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "EDIT_REASON"))) {
                // line 316
                echo "\t\t\t\t<div class=\"notice\">
\t\t\t\t\t";
                // line 317
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "EDITED_MESSAGE");
                echo "
\t\t\t\t\t";
                // line 318
                if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "EDIT_REASON")) {
                    echo "<br /><strong>";
                    echo $this->env->getExtension('phpbb')->lang("REASON");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</strong> <em>";
                    echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "EDIT_REASON");
                    echo "</em>";
                }
                // line 319
                echo "\t\t\t\t</div>
\t\t\t";
            }
            // line 321
            echo "
\t\t\t";
            // line 322
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "BUMPED_MESSAGE")) {
                echo "<div class=\"notice\"><br /><br />";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "BUMPED_MESSAGE");
                echo "</div>";
            }
            // line 323
            echo "\t\t\t";
            // line 324
            echo "\t\t\t";
            if ($this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "SIGNATURE")) {
                echo "<div id=\"sig";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "POST_ID");
                echo "\" class=\"signature\">";
                echo $this->getAttribute((isset($context["postrow"]) ? $context["postrow"] : null), "SIGNATURE");
                echo "</div>";
            }
            // line 325
            echo "
\t\t\t";
            // line 326
            // line 327
            echo "\t\t\t</div>

\t\t</div>

\t\t</div>
\t\t<div class=\"back2top\"><a href=\"#top\" class=\"top\" title=\"";
            // line 332
            echo $this->env->getExtension('phpbb')->lang("BACK_TO_TOP");
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("BACK_TO_TOP");
            echo "</a></div>
\t</div>

\t";
            // line 335
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['postrow'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 337
        echo "
";
        // line 338
        if ((isset($context["S_QUICK_REPLY"]) ? $context["S_QUICK_REPLY"] : null)) {
            // line 339
            echo "\t";
            $location = "quickreply_editor.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->env->loadTemplate("quickreply_editor.html")->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
        // line 341
        echo "
";
        // line 342
        if ((((isset($context["S_NUM_POSTS"]) ? $context["S_NUM_POSTS"] : null) > 1) || twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination")))) {
            // line 343
            echo "\t<form id=\"viewtopic\" method=\"post\" action=\"";
            echo (isset($context["S_TOPIC_ACTION"]) ? $context["S_TOPIC_ACTION"] : null);
            echo "\">
\t<fieldset class=\"display-options\" style=\"margin-top: 0; \">
\t\t";
            // line 345
            if ((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null))) {
                // line 346
                echo "\t\t<label>";
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_POSTS");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo " ";
                echo (isset($context["S_SELECT_SORT_DAYS"]) ? $context["S_SELECT_SORT_DAYS"] : null);
                echo "</label>
\t\t<label>";
                // line 347
                echo $this->env->getExtension('phpbb')->lang("SORT_BY");
                echo " ";
                echo (isset($context["S_SELECT_SORT_KEY"]) ? $context["S_SELECT_SORT_KEY"] : null);
                echo "</label> <label>";
                echo (isset($context["S_SELECT_SORT_DIR"]) ? $context["S_SELECT_SORT_DIR"] : null);
                echo "</label>
\t\t<input type=\"submit\" name=\"sort\" value=\"";
                // line 348
                echo $this->env->getExtension('phpbb')->lang("GO");
                echo "\" class=\"button2\" />
\t\t";
            }
            // line 350
            echo "\t</fieldset>
\t</form>
\t<hr />
";
        }
        // line 354
        echo "
";
        // line 355
        // line 356
        echo "<div class=\"action-bar bottom\">
\t<div class=\"buttons\">
\t\t";
        // line 358
        // line 359
        echo "
\t";
        // line 360
        if (((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) && (isset($context["S_DISPLAY_REPLY_INFO"]) ? $context["S_DISPLAY_REPLY_INFO"] : null))) {
            // line 361
            echo "\t\t<a href=\"";
            echo (isset($context["U_POST_REPLY_TOPIC"]) ? $context["U_POST_REPLY_TOPIC"] : null);
            echo "\" class=\"button icon-button ";
            if ((isset($context["S_IS_LOCKED"]) ? $context["S_IS_LOCKED"] : null)) {
                echo "locked-icon";
            } else {
                echo "reply-icon";
            }
            echo "\" title=\"";
            if ((isset($context["S_IS_LOCKED"]) ? $context["S_IS_LOCKED"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("TOPIC_LOCKED");
            } else {
                echo $this->env->getExtension('phpbb')->lang("POST_REPLY");
            }
            echo "\">
\t\t\t";
            // line 362
            if ((isset($context["S_IS_LOCKED"]) ? $context["S_IS_LOCKED"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("BUTTON_TOPIC_LOCKED");
            } else {
                echo $this->env->getExtension('phpbb')->lang("BUTTON_POST_REPLY");
            }
            // line 363
            echo "\t\t</a>
\t";
        }
        // line 365
        echo "
\t\t";
        // line 366
        // line 367
        echo "\t</div>

\t";
        // line 369
        $location = "viewtopic_topic_tools.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("viewtopic_topic_tools.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 370
        echo "
\t";
        // line 371
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "quickmod"))) {
            // line 372
            echo "\t\t<div class=\"dropdown-container dropdown-container-";
            echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
            echo " dropdown-up dropdown-";
            echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
            echo " dropdown-button-control\" id=\"quickmod\">
\t\t\t<span title=\"";
            // line 373
            echo $this->env->getExtension('phpbb')->lang("QUICK_MOD");
            echo "\" class=\"dropdown-trigger button icon-button modtools-icon dropdown-select\">";
            echo $this->env->getExtension('phpbb')->lang("QUICK_MOD");
            echo "</span>
\t\t\t<div class=\"dropdown hidden\">
\t\t\t\t<div class=\"pointer\"><div class=\"pointer-inner\"></div></div>
\t\t\t\t<ul class=\"dropdown-contents\">
\t\t\t\t";
            // line 377
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "quickmod"));
            foreach ($context['_seq'] as $context["_key"] => $context["quickmod"]) {
                // line 378
                echo "\t\t\t\t\t";
                $value = twig_in_filter($this->getAttribute((isset($context["quickmod"]) ? $context["quickmod"] : null), "VALUE"), array(0 => "lock", 1 => "unlock", 2 => "delete_topic", 3 => "restore_topic", 4 => "make_normal", 5 => "make_sticky", 6 => "make_announce", 7 => "make_global"));
                $context['definition']->set('QUICKMOD_AJAX', $value);
                // line 379
                echo "\t\t\t\t\t<li><a href=\"";
                echo $this->getAttribute((isset($context["quickmod"]) ? $context["quickmod"] : null), "LINK");
                echo "\"";
                if ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "QUICKMOD_AJAX")) {
                    echo " data-ajax=\"true\" data-refresh=\"true\"";
                }
                echo ">";
                echo $this->getAttribute((isset($context["quickmod"]) ? $context["quickmod"] : null), "TITLE");
                echo "</a></li>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['quickmod'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 381
            echo "\t\t\t\t</ul>
\t\t\t</div>
\t\t</div>
\t";
        }
        // line 385
        echo "
\t";
        // line 386
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination")) || (isset($context["TOTAL_POSTS"]) ? $context["TOTAL_POSTS"] : null))) {
            // line 387
            echo "\t\t<div class=\"pagination\">
\t\t\t";
            // line 388
            echo (isset($context["TOTAL_POSTS"]) ? $context["TOTAL_POSTS"] : null);
            echo "
\t\t\t";
            // line 389
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                // line 390
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
                // line 391
                echo "\t\t\t";
            } else {
                // line 392
                echo "\t\t\t\t&bull; ";
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t";
            }
            // line 394
            echo "\t\t</div>
\t";
        }
        // line 396
        echo "\t<div class=\"clear\"></div>
</div>

";
        // line 399
        // line 400
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
        // line 401
        echo "
";
        // line 402
        if ((isset($context["S_DISPLAY_ONLINE_LIST"]) ? $context["S_DISPLAY_ONLINE_LIST"] : null)) {
            // line 403
            echo "\t<div class=\"stat-block online-list\">
\t\t<h3>";
            // line 404
            if ((isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null)) {
                echo "<a href=\"";
                echo (isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("WHO_IS_ONLINE");
                echo "</a>";
            } else {
                echo $this->env->getExtension('phpbb')->lang("WHO_IS_ONLINE");
            }
            echo "</h3>
\t\t<p>";
            // line 405
            echo (isset($context["LOGGED_IN_USER_LIST"]) ? $context["LOGGED_IN_USER_LIST"] : null);
            echo "</p>
\t</div>
";
        }
        // line 408
        echo "
";
        // line 409
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
        return "viewtopic_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1501 => 409,  1498 => 408,  1492 => 405,  1480 => 404,  1477 => 403,  1475 => 402,  1472 => 401,  1460 => 400,  1459 => 399,  1454 => 396,  1450 => 394,  1444 => 392,  1441 => 391,  1428 => 390,  1426 => 389,  1422 => 388,  1419 => 387,  1417 => 386,  1414 => 385,  1408 => 381,  1393 => 379,  1389 => 378,  1385 => 377,  1376 => 373,  1369 => 372,  1367 => 371,  1364 => 370,  1352 => 369,  1348 => 367,  1347 => 366,  1344 => 365,  1340 => 363,  1334 => 362,  1317 => 361,  1315 => 360,  1312 => 359,  1311 => 358,  1307 => 356,  1306 => 355,  1303 => 354,  1297 => 350,  1292 => 348,  1284 => 347,  1276 => 346,  1274 => 345,  1268 => 343,  1266 => 342,  1263 => 341,  1249 => 339,  1247 => 338,  1244 => 337,  1239 => 335,  1231 => 332,  1224 => 327,  1223 => 326,  1220 => 325,  1211 => 324,  1209 => 323,  1203 => 322,  1200 => 321,  1196 => 319,  1187 => 318,  1183 => 317,  1180 => 316,  1176 => 314,  1167 => 313,  1163 => 312,  1160 => 311,  1157 => 310,  1150 => 309,  1149 => 308,  1146 => 307,  1142 => 305,  1133 => 303,  1129 => 302,  1124 => 300,  1120 => 298,  1118 => 297,  1113 => 295,  1110 => 294,  1102 => 291,  1099 => 290,  1097 => 289,  1094 => 288,  1087 => 284,  1083 => 283,  1079 => 282,  1075 => 281,  1071 => 280,  1065 => 278,  1058 => 274,  1054 => 273,  1050 => 272,  1046 => 271,  1042 => 270,  1036 => 268,  1034 => 267,  1015 => 265,  1012 => 264,  1009 => 263,  1005 => 261,  1003 => 260,  993 => 257,  990 => 256,  987 => 255,  977 => 252,  974 => 251,  971 => 250,  961 => 247,  958 => 246,  955 => 245,  945 => 242,  942 => 241,  939 => 240,  929 => 237,  926 => 236,  923 => 235,  913 => 232,  910 => 231,  907 => 230,  906 => 229,  903 => 228,  900 => 227,  898 => 226,  876 => 224,  866 => 222,  863 => 221,  857 => 218,  853 => 217,  848 => 216,  842 => 213,  838 => 212,  833 => 211,  830 => 210,  828 => 209,  821 => 204,  819 => 203,  812 => 198,  806 => 197,  802 => 195,  800 => 194,  793 => 192,  775 => 191,  771 => 189,  768 => 188,  764 => 187,  761 => 186,  757 => 185,  748 => 181,  744 => 180,  739 => 179,  736 => 178,  733 => 177,  732 => 176,  729 => 175,  727 => 174,  721 => 173,  710 => 171,  707 => 170,  702 => 169,  701 => 168,  698 => 167,  690 => 165,  687 => 164,  685 => 163,  682 => 162,  672 => 161,  662 => 160,  645 => 159,  642 => 158,  632 => 157,  628 => 155,  626 => 154,  617 => 153,  616 => 152,  613 => 151,  611 => 150,  608 => 149,  595 => 148,  592 => 147,  591 => 146,  576 => 144,  568 => 143,  540 => 140,  530 => 138,  527 => 137,  525 => 136,  521 => 135,  518 => 134,  510 => 129,  506 => 128,  499 => 124,  496 => 123,  488 => 120,  484 => 118,  482 => 117,  479 => 116,  473 => 113,  469 => 111,  467 => 110,  458 => 107,  451 => 105,  448 => 104,  442 => 103,  441 => 102,  428 => 100,  405 => 99,  379 => 98,  367 => 97,  349 => 96,  347 => 95,  343 => 94,  329 => 91,  325 => 90,  315 => 84,  313 => 83,  308 => 80,  304 => 78,  298 => 76,  295 => 75,  282 => 74,  280 => 73,  269 => 72,  266 => 71,  264 => 70,  261 => 69,  253 => 64,  245 => 63,  239 => 62,  235 => 61,  230 => 59,  227 => 58,  225 => 57,  222 => 56,  210 => 55,  206 => 53,  205 => 52,  202 => 51,  198 => 49,  192 => 48,  175 => 47,  173 => 46,  170 => 45,  169 => 44,  163 => 40,  157 => 36,  152 => 34,  147 => 33,  139 => 31,  137 => 30,  128 => 27,  126 => 26,  123 => 25,  110 => 22,  107 => 21,  105 => 20,  102 => 19,  96 => 18,  87 => 16,  84 => 15,  72 => 14,  66 => 11,  46 => 8,  37 => 7,  34 => 6,  26 => 4,  23 => 3,  21 => 2,  19 => 1,);
    }
}
