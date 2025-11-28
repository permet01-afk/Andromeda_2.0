<?php

/* memberlist_search.html */
class __TwigTemplate_6f208ab334a5380480f16c49e1813351 extends Twig_Template
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
        echo "<h2 class=\"solo\">";
        echo $this->env->getExtension('phpbb')->lang("FIND_USERNAME");
        echo "</h2>

<form method=\"post\" action=\"";
        // line 3
        echo (isset($context["S_MODE_ACTION"]) ? $context["S_MODE_ACTION"] : null);
        echo "\" id=\"search_memberlist\">
<div class=\"panel\">
\t<div class=\"inner\">

\t<p>";
        // line 7
        echo $this->env->getExtension('phpbb')->lang("FIND_USERNAME_EXPLAIN");
        echo "</p>

\t";
        // line 9
        // line 10
        echo "\t<fieldset class=\"fields1 column1\">
\t<dl style=\"overflow: visible;\">
\t\t<dt><label for=\"username\">";
        // line 12
        echo $this->env->getExtension('phpbb')->lang("USERNAME");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t<dd>
\t\t\t";
        // line 14
        if ((isset($context["U_LIVE_SEARCH"]) ? $context["U_LIVE_SEARCH"] : null)) {
            echo "<div class=\"dropdown-container dropdown-";
            echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
            echo "\">";
        }
        // line 15
        echo "\t\t\t<input type=\"text\" name=\"username\" id=\"username\" value=\"";
        echo (isset($context["USERNAME"]) ? $context["USERNAME"] : null);
        echo "\" class=\"inputbox\"";
        if ((isset($context["U_LIVE_SEARCH"]) ? $context["U_LIVE_SEARCH"] : null)) {
            echo " autocomplete=\"off\" data-filter=\"phpbb.search.filter\" data-ajax=\"member_search\" data-min-length=\"3\" data-url=\"";
            echo (isset($context["U_LIVE_SEARCH"]) ? $context["U_LIVE_SEARCH"] : null);
            echo "\" data-results=\"#user-search\" data-overlay=\"false\"";
        }
        echo " />
\t\t\t";
        // line 16
        if ((isset($context["U_LIVE_SEARCH"]) ? $context["U_LIVE_SEARCH"] : null)) {
            // line 17
            echo "\t\t\t\t<div class=\"dropdown live-search hidden\" id=\"user-search\">
\t\t\t\t\t<div class=\"pointer\"><div class=\"pointer-inner\"></div></div>
\t\t\t\t\t<ul class=\"dropdown-contents search-results\">
\t\t\t\t\t\t<li class=\"search-result-tpl\"><span class=\"search-result\"></span></li>
\t\t\t\t\t</ul>
\t\t\t\t</div>
\t\t\t</div>
\t\t\t";
        }
        // line 25
        echo "\t\t</dd>
\t</dl>
";
        // line 27
        if ((isset($context["S_EMAIL_SEARCH_ALLOWED"]) ? $context["S_EMAIL_SEARCH_ALLOWED"] : null)) {
            // line 28
            echo "\t<dl>
\t\t<dt><label for=\"email\">";
            // line 29
            echo $this->env->getExtension('phpbb')->lang("EMAIL");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd><input type=\"text\" name=\"email\" id=\"email\" value=\"";
            // line 30
            echo (isset($context["EMAIL"]) ? $context["EMAIL"] : null);
            echo "\" class=\"inputbox\" /></dd>
\t</dl>
";
        }
        // line 33
        if ((isset($context["S_JABBER_ENABLED"]) ? $context["S_JABBER_ENABLED"] : null)) {
            // line 34
            echo "\t<dl>
\t\t<dt><label for=\"jabber\">";
            // line 35
            echo $this->env->getExtension('phpbb')->lang("JABBER");
            echo ":</label></dt>
\t\t<dd><input type=\"text\" name=\"jabber\" id=\"jabber\" value=\"";
            // line 36
            echo (isset($context["JABBER"]) ? $context["JABBER"] : null);
            echo "\" class=\"inputbox\" /></dd>
\t</dl>
";
        }
        // line 39
        echo "\t<dl>
\t\t<dt><label for=\"search_group_id\">";
        // line 40
        echo $this->env->getExtension('phpbb')->lang("GROUP");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t<dd><select name=\"search_group_id\" id=\"search_group_id\">";
        // line 41
        echo (isset($context["S_GROUP_SELECT"]) ? $context["S_GROUP_SELECT"] : null);
        echo "</select></dd>
\t</dl>
\t";
        // line 43
        // line 44
        echo "\t<dl>
\t\t<dt><label for=\"sk\" class=\"label3\">";
        // line 45
        echo $this->env->getExtension('phpbb')->lang("SORT_BY");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t<dd><select name=\"sk\" id=\"sk\">";
        // line 46
        echo (isset($context["S_SORT_OPTIONS"]) ? $context["S_SORT_OPTIONS"] : null);
        echo "</select> <select name=\"sd\">";
        echo (isset($context["S_ORDER_SELECT"]) ? $context["S_ORDER_SELECT"] : null);
        echo "</select></dd>
\t</dl>
\t</fieldset>

\t<fieldset class=\"fields1 column2\">
\t<dl>
\t\t<dt><label for=\"joined\">";
        // line 52
        echo $this->env->getExtension('phpbb')->lang("JOINED");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t<dd><select name=\"joined_select\">";
        // line 53
        echo (isset($context["S_JOINED_TIME_OPTIONS"]) ? $context["S_JOINED_TIME_OPTIONS"] : null);
        echo "</select> <input class=\"inputbox medium\" type=\"text\" name=\"joined\" id=\"joined\" value=\"";
        echo (isset($context["JOINED"]) ? $context["JOINED"] : null);
        echo "\" /></dd>
\t</dl>
";
        // line 55
        if ((isset($context["S_VIEWONLINE"]) ? $context["S_VIEWONLINE"] : null)) {
            // line 56
            echo "\t<dl>
\t\t<dt><label for=\"active\">";
            // line 57
            echo $this->env->getExtension('phpbb')->lang("LAST_ACTIVE");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd><select name=\"active_select\">";
            // line 58
            echo (isset($context["S_ACTIVE_TIME_OPTIONS"]) ? $context["S_ACTIVE_TIME_OPTIONS"] : null);
            echo "</select> <input class=\"inputbox medium\" type=\"text\" name=\"active\" id=\"active\" value=\"";
            echo (isset($context["ACTIVE"]) ? $context["ACTIVE"] : null);
            echo "\" /></dd>
\t</dl>
";
        }
        // line 61
        echo "\t<dl>
\t\t<dt><label for=\"count\">";
        // line 62
        echo $this->env->getExtension('phpbb')->lang("POSTS");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t<dd><select name=\"count_select\">";
        // line 63
        echo (isset($context["S_COUNT_OPTIONS"]) ? $context["S_COUNT_OPTIONS"] : null);
        echo "</select> <input class=\"inputbox medium\" type=\"number\" min=\"0\" name=\"count\" id=\"count\" value=\"";
        echo (isset($context["COUNT"]) ? $context["COUNT"] : null);
        echo "\" /></dd>
\t</dl>
";
        // line 65
        if ((isset($context["S_IP_SEARCH_ALLOWED"]) ? $context["S_IP_SEARCH_ALLOWED"] : null)) {
            // line 66
            echo "\t<dl>
\t\t<dt><label for=\"ip\">";
            // line 67
            echo $this->env->getExtension('phpbb')->lang("POST_IP");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd><input class=\"inputbox medium\" type=\"text\" name=\"ip\" id=\"ip\" value=\"";
            // line 68
            echo (isset($context["IP"]) ? $context["IP"] : null);
            echo "\" /></dd>
\t</dl>
";
        }
        // line 71
        echo "\t";
        // line 72
        echo "\t</fieldset>

\t<div class=\"clear\"></div>

\t<hr />

\t<fieldset class=\"submit-buttons\">
\t\t<input type=\"reset\" value=\"";
        // line 79
        echo $this->env->getExtension('phpbb')->lang("RESET");
        echo "\" name=\"reset\" class=\"button2\" />&nbsp;
\t\t<input type=\"submit\" name=\"submit\" value=\"";
        // line 80
        echo $this->env->getExtension('phpbb')->lang("SEARCH");
        echo "\" class=\"button1\" />
\t\t";
        // line 81
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t</fieldset>

\t</div>
</div>

</form>
";
    }

    public function getTemplateName()
    {
        return "memberlist_search.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  225 => 81,  221 => 80,  208 => 72,  206 => 71,  200 => 68,  195 => 67,  190 => 65,  183 => 63,  178 => 62,  175 => 61,  159 => 56,  157 => 55,  150 => 53,  145 => 52,  134 => 46,  125 => 43,  120 => 41,  115 => 40,  112 => 39,  106 => 36,  102 => 35,  99 => 34,  97 => 33,  91 => 30,  86 => 29,  81 => 27,  77 => 25,  67 => 17,  65 => 16,  54 => 15,  48 => 14,  42 => 12,  38 => 10,  37 => 9,  32 => 7,  25 => 3,  739 => 165,  726 => 164,  712 => 162,  710 => 161,  705 => 158,  700 => 156,  695 => 155,  683 => 154,  679 => 153,  675 => 152,  668 => 147,  662 => 144,  656 => 143,  649 => 142,  646 => 141,  644 => 140,  641 => 139,  636 => 137,  633 => 136,  631 => 135,  628 => 134,  620 => 131,  616 => 130,  613 => 129,  611 => 128,  603 => 122,  588 => 119,  585 => 118,  579 => 116,  573 => 115,  569 => 114,  551 => 113,  537 => 112,  512 => 111,  504 => 110,  501 => 109,  498 => 108,  495 => 107,  492 => 106,  487 => 103,  484 => 102,  478 => 101,  474 => 100,  459 => 99,  455 => 98,  450 => 97,  447 => 96,  439 => 95,  433 => 94,  418 => 93,  412 => 92,  393 => 91,  391 => 90,  383 => 84,  375 => 78,  373 => 77,  363 => 74,  360 => 73,  357 => 72,  354 => 71,  351 => 70,  345 => 69,  339 => 65,  331 => 64,  325 => 63,  310 => 62,  304 => 61,  282 => 60,  274 => 54,  272 => 53,  269 => 52,  264 => 49,  259 => 47,  254 => 46,  242 => 45,  238 => 44,  234 => 43,  228 => 39,  217 => 79,  213 => 36,  210 => 35,  192 => 66,  176 => 30,  172 => 28,  167 => 58,  162 => 57,  158 => 25,  151 => 23,  140 => 22,  138 => 21,  135 => 20,  129 => 45,  126 => 44,  114 => 15,  107 => 14,  92 => 13,  83 => 28,  73 => 10,  63 => 8,  60 => 7,  58 => 6,  47 => 4,  34 => 3,  21 => 2,  19 => 1,);
    }
}
