<?php

/* navbar_footer.html */
class __TwigTemplate_26cd4b147a7be7a82133446816ab9d83 extends Twig_Template
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
        echo "<div class=\"navbar\" role=\"navigation\">
\t<div class=\"inner\">

\t<ul id=\"nav-footer\" class=\"linklist bulletin\" role=\"menubar\">
\t\t<li class=\"small-icon icon-home breadcrumbs\">
\t\t\t";
        // line 6
        if ((isset($context["U_SITE_HOME"]) ? $context["U_SITE_HOME"] : null)) {
            echo "<span class=\"crumb\"><a href=\"";
            echo (isset($context["U_SITE_HOME"]) ? $context["U_SITE_HOME"] : null);
            echo "\" data-navbar-reference=\"home\">";
            echo $this->env->getExtension('phpbb')->lang("SITE_HOME");
            echo "</a></span>";
        }
        // line 7
        echo "\t\t\t";
        // line 8
        echo "\t\t\t<span class=\"crumb\"><a href=\"";
        echo (isset($context["U_INDEX"]) ? $context["U_INDEX"] : null);
        echo "\" data-navbar-reference=\"index\">";
        echo $this->env->getExtension('phpbb')->lang("INDEX");
        echo "</a></span>
\t\t\t";
        // line 9
        // line 10
        echo "\t\t</li>
\t\t";
        // line 11
        if (((isset($context["U_WATCH_FORUM_LINK"]) ? $context["U_WATCH_FORUM_LINK"] : null) && (!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)))) {
            echo "<li class=\"small-icon icon-";
            if ((isset($context["S_WATCHING_FORUM"]) ? $context["S_WATCHING_FORUM"] : null)) {
                echo "unsubscribe";
            } else {
                echo "subscribe";
            }
            echo "\" data-last-responsive=\"true\"><a href=\"";
            echo (isset($context["U_WATCH_FORUM_LINK"]) ? $context["U_WATCH_FORUM_LINK"] : null);
            echo "\" title=\"";
            echo (isset($context["S_WATCH_FORUM_TITLE"]) ? $context["S_WATCH_FORUM_TITLE"] : null);
            echo "\" data-ajax=\"toggle_link\" data-toggle-class=\"small-icon icon-";
            if ((!(isset($context["S_WATCHING_FORUM"]) ? $context["S_WATCHING_FORUM"] : null))) {
                echo "unsubscribe";
            } else {
                echo "subscribe";
            }
            echo "\" data-toggle-text=\"";
            echo (isset($context["S_WATCH_FORUM_TOGGLE"]) ? $context["S_WATCH_FORUM_TOGGLE"] : null);
            echo "\" data-toggle-url=\"";
            echo (isset($context["U_WATCH_FORUM_TOGGLE"]) ? $context["U_WATCH_FORUM_TOGGLE"] : null);
            echo "\">";
            echo (isset($context["S_WATCH_FORUM_TITLE"]) ? $context["S_WATCH_FORUM_TITLE"] : null);
            echo "</a></li>";
        }
        // line 12
        echo "
\t\t";
        // line 13
        // line 14
        echo "\t\t<li class=\"rightside\">";
        echo (isset($context["S_TIMEZONE"]) ? $context["S_TIMEZONE"] : null);
        echo "</li>
\t\t";
        // line 15
        // line 16
        echo "\t\t";
        if ((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null))) {
            // line 17
            echo "\t\t\t<li class=\"small-icon icon-delete-cookies rightside\"><a href=\"";
            echo (isset($context["U_DELETE_COOKIES"]) ? $context["U_DELETE_COOKIES"] : null);
            echo "\" data-ajax=\"true\" data-refresh=\"true\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb')->lang("DELETE_COOKIES");
            echo "</a></li>
\t\t\t";
            // line 18
            if ((isset($context["S_DISPLAY_MEMBERLIST"]) ? $context["S_DISPLAY_MEMBERLIST"] : null)) {
                echo "<li class=\"small-icon icon-members rightside\" data-last-responsive=\"true\"><a href=\"";
                echo (isset($context["U_MEMBERLIST"]) ? $context["U_MEMBERLIST"] : null);
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("MEMBERLIST_EXPLAIN");
                echo "\" role=\"menuitem\">";
                echo $this->env->getExtension('phpbb')->lang("MEMBERLIST");
                echo "</a></li>";
            }
            // line 19
            echo "\t\t";
        }
        // line 20
        echo "\t\t";
        // line 21
        echo "\t\t";
        if ((isset($context["U_TEAM"]) ? $context["U_TEAM"] : null)) {
            echo "<li class=\"small-icon icon-team rightside\" data-last-responsive=\"true\"><a href=\"";
            echo (isset($context["U_TEAM"]) ? $context["U_TEAM"] : null);
            echo "\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb')->lang("THE_TEAM");
            echo "</a></li>";
        }
        // line 22
        echo "\t\t";
        // line 23
        echo "\t\t";
        if ((isset($context["U_CONTACT_US"]) ? $context["U_CONTACT_US"] : null)) {
            echo "<li class=\"small-icon icon-contact rightside\" data-last-responsive=\"true\"><a href=\"";
            echo (isset($context["U_CONTACT_US"]) ? $context["U_CONTACT_US"] : null);
            echo "\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb')->lang("CONTACT_US");
            echo "</a></li>";
        }
        // line 24
        echo "\t</ul>

\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "navbar_footer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  128 => 24,  119 => 23,  43 => 9,  34 => 7,  233 => 62,  232 => 61,  227 => 59,  206 => 55,  191 => 54,  169 => 52,  157 => 50,  149 => 47,  140 => 42,  123 => 31,  107 => 26,  103 => 19,  94 => 23,  86 => 17,  81 => 20,  74 => 17,  48 => 10,  44 => 10,  29 => 6,  26 => 6,  160 => 39,  145 => 35,  142 => 34,  118 => 29,  112 => 28,  105 => 26,  96 => 25,  84 => 23,  82 => 15,  54 => 13,  46 => 11,  38 => 9,  30 => 7,  25 => 4,  657 => 193,  652 => 192,  650 => 191,  644 => 188,  634 => 186,  632 => 185,  629 => 184,  628 => 183,  625 => 182,  623 => 181,  617 => 180,  616 => 179,  603 => 178,  601 => 177,  597 => 176,  588 => 175,  586 => 174,  576 => 173,  573 => 172,  571 => 171,  568 => 170,  564 => 168,  562 => 167,  557 => 164,  551 => 162,  549 => 161,  546 => 160,  543 => 159,  535 => 157,  532 => 156,  524 => 154,  522 => 153,  513 => 152,  510 => 151,  507 => 150,  499 => 148,  497 => 147,  490 => 146,  487 => 145,  484 => 144,  481 => 143,  473 => 141,  471 => 140,  464 => 139,  462 => 138,  457 => 137,  454 => 136,  451 => 135,  419 => 132,  416 => 131,  410 => 129,  405 => 127,  403 => 126,  396 => 124,  389 => 119,  376 => 115,  374 => 114,  365 => 113,  355 => 111,  352 => 110,  349 => 109,  345 => 107,  333 => 106,  327 => 103,  313 => 100,  310 => 99,  302 => 94,  295 => 92,  288 => 91,  275 => 86,  271 => 84,  270 => 83,  253 => 80,  249 => 78,  246 => 77,  229 => 72,  223 => 70,  213 => 58,  207 => 65,  196 => 64,  186 => 61,  182 => 59,  178 => 57,  153 => 48,  144 => 44,  141 => 45,  139 => 44,  125 => 30,  120 => 34,  113 => 32,  92 => 27,  87 => 26,  79 => 24,  68 => 21,  66 => 20,  63 => 20,  57 => 17,  47 => 11,  39 => 8,  32 => 5,  23 => 3,  70 => 15,  64 => 33,  61 => 18,  58 => 17,  55 => 30,  52 => 12,  49 => 28,  21 => 2,  449 => 134,  446 => 133,  436 => 116,  432 => 114,  430 => 113,  426 => 112,  421 => 109,  420 => 108,  417 => 107,  413 => 105,  411 => 104,  407 => 128,  395 => 101,  392 => 100,  386 => 118,  384 => 117,  378 => 91,  375 => 90,  370 => 89,  361 => 84,  353 => 83,  347 => 82,  339 => 81,  334 => 79,  331 => 78,  328 => 77,  326 => 76,  320 => 101,  316 => 72,  312 => 71,  294 => 70,  286 => 90,  282 => 62,  280 => 61,  277 => 60,  276 => 59,  264 => 56,  260 => 54,  259 => 53,  254 => 51,  251 => 50,  250 => 79,  242 => 46,  238 => 76,  228 => 41,  225 => 40,  217 => 38,  215 => 37,  210 => 57,  204 => 33,  198 => 31,  194 => 29,  192 => 28,  189 => 27,  185 => 26,  179 => 25,  176 => 53,  170 => 52,  168 => 21,  165 => 42,  147 => 18,  121 => 16,  110 => 15,  99 => 29,  88 => 22,  77 => 14,  75 => 18,  72 => 36,  60 => 9,  35 => 7,  31 => 7,  22 => 2,  261 => 81,  256 => 65,  245 => 59,  234 => 57,  230 => 42,  226 => 71,  220 => 51,  218 => 50,  212 => 46,  209 => 56,  195 => 63,  193 => 43,  183 => 60,  177 => 35,  174 => 34,  167 => 31,  161 => 51,  158 => 29,  155 => 28,  152 => 27,  138 => 41,  134 => 25,  132 => 31,  129 => 23,  126 => 38,  117 => 22,  108 => 21,  106 => 20,  100 => 18,  93 => 18,  83 => 16,  76 => 13,  73 => 12,  67 => 14,  59 => 10,  50 => 12,  36 => 8,  33 => 6,  19 => 1,);
    }
}
