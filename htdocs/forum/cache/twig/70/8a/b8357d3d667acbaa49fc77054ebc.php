<?php

/* navbar_footer.html */
class __TwigTemplate_708ab8357d3d667acbaa49fc77054ebc extends Twig_Template
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
        return array (  119 => 23,  108 => 21,  77 => 14,  73 => 12,  43 => 9,  233 => 62,  232 => 61,  209 => 56,  191 => 54,  149 => 47,  140 => 42,  138 => 41,  103 => 19,  94 => 23,  88 => 22,  86 => 17,  81 => 20,  75 => 18,  48 => 10,  29 => 6,  25 => 4,  131 => 25,  114 => 23,  111 => 22,  101 => 17,  83 => 16,  74 => 17,  71 => 13,  69 => 12,  54 => 13,  22 => 2,  117 => 22,  115 => 21,  104 => 20,  93 => 18,  82 => 15,  44 => 10,  35 => 9,  31 => 7,  24 => 3,  657 => 193,  652 => 192,  650 => 191,  644 => 188,  634 => 186,  629 => 184,  625 => 182,  623 => 181,  603 => 178,  601 => 177,  597 => 176,  588 => 175,  586 => 174,  573 => 172,  571 => 171,  564 => 168,  562 => 167,  557 => 164,  551 => 162,  549 => 161,  546 => 160,  543 => 159,  535 => 157,  532 => 156,  524 => 154,  522 => 153,  513 => 152,  507 => 150,  497 => 147,  490 => 146,  487 => 145,  481 => 143,  471 => 140,  464 => 139,  462 => 138,  457 => 137,  454 => 136,  449 => 134,  446 => 133,  419 => 132,  416 => 131,  410 => 129,  407 => 128,  403 => 126,  396 => 124,  389 => 119,  386 => 118,  384 => 117,  376 => 115,  374 => 114,  365 => 113,  355 => 111,  352 => 110,  345 => 107,  333 => 106,  327 => 103,  320 => 101,  310 => 99,  302 => 94,  288 => 91,  286 => 90,  275 => 86,  271 => 84,  270 => 83,  250 => 79,  249 => 78,  246 => 77,  238 => 76,  229 => 72,  226 => 71,  223 => 70,  213 => 58,  207 => 65,  196 => 64,  195 => 63,  186 => 61,  183 => 60,  182 => 59,  178 => 57,  176 => 53,  161 => 51,  153 => 48,  144 => 44,  141 => 45,  132 => 42,  125 => 37,  120 => 34,  113 => 32,  106 => 20,  99 => 29,  92 => 27,  79 => 24,  76 => 13,  68 => 21,  63 => 19,  57 => 16,  50 => 12,  47 => 11,  39 => 8,  36 => 8,  33 => 6,  32 => 8,  70 => 15,  67 => 14,  64 => 13,  61 => 18,  58 => 17,  55 => 30,  52 => 12,  49 => 28,  1501 => 409,  1498 => 408,  1492 => 405,  1480 => 404,  1477 => 403,  1475 => 402,  1472 => 401,  1460 => 400,  1459 => 399,  1454 => 396,  1450 => 394,  1444 => 392,  1441 => 391,  1428 => 390,  1426 => 389,  1422 => 388,  1419 => 387,  1417 => 386,  1414 => 385,  1408 => 381,  1393 => 379,  1389 => 378,  1385 => 377,  1376 => 373,  1369 => 372,  1367 => 371,  1364 => 370,  1352 => 369,  1348 => 367,  1347 => 366,  1344 => 365,  1340 => 363,  1334 => 362,  1317 => 361,  1315 => 360,  1312 => 359,  1311 => 358,  1307 => 356,  1306 => 355,  1303 => 354,  1297 => 350,  1292 => 348,  1284 => 347,  1276 => 346,  1274 => 345,  1268 => 343,  1266 => 342,  1263 => 341,  1249 => 339,  1247 => 338,  1244 => 337,  1239 => 335,  1231 => 332,  1224 => 327,  1223 => 326,  1220 => 325,  1211 => 324,  1209 => 323,  1203 => 322,  1200 => 321,  1196 => 319,  1187 => 318,  1183 => 317,  1180 => 316,  1176 => 314,  1167 => 313,  1163 => 312,  1160 => 311,  1157 => 310,  1150 => 309,  1149 => 308,  1146 => 307,  1142 => 305,  1133 => 303,  1129 => 302,  1124 => 300,  1120 => 298,  1118 => 297,  1113 => 295,  1110 => 294,  1102 => 291,  1099 => 290,  1097 => 289,  1094 => 288,  1087 => 284,  1083 => 283,  1079 => 282,  1075 => 281,  1071 => 280,  1065 => 278,  1058 => 274,  1054 => 273,  1050 => 272,  1046 => 271,  1042 => 270,  1036 => 268,  1034 => 267,  1015 => 265,  1012 => 264,  1009 => 263,  1005 => 261,  1003 => 260,  993 => 257,  990 => 256,  987 => 255,  977 => 252,  974 => 251,  971 => 250,  961 => 247,  958 => 246,  955 => 245,  945 => 242,  942 => 241,  939 => 240,  929 => 237,  926 => 236,  923 => 235,  913 => 232,  910 => 231,  907 => 230,  906 => 229,  903 => 228,  900 => 227,  898 => 226,  876 => 224,  866 => 222,  863 => 221,  857 => 218,  853 => 217,  848 => 216,  842 => 213,  838 => 212,  833 => 211,  830 => 210,  828 => 209,  821 => 204,  819 => 203,  812 => 198,  806 => 197,  802 => 195,  800 => 194,  793 => 192,  775 => 191,  771 => 189,  768 => 188,  764 => 187,  761 => 186,  757 => 185,  748 => 181,  744 => 180,  739 => 179,  736 => 178,  733 => 177,  732 => 176,  729 => 175,  727 => 174,  721 => 173,  710 => 171,  707 => 170,  702 => 169,  701 => 168,  698 => 167,  690 => 165,  687 => 164,  685 => 163,  682 => 162,  672 => 161,  662 => 160,  645 => 159,  642 => 158,  632 => 185,  628 => 183,  626 => 154,  617 => 180,  616 => 179,  613 => 151,  611 => 150,  608 => 149,  595 => 148,  592 => 147,  591 => 146,  576 => 173,  568 => 170,  540 => 140,  530 => 138,  527 => 137,  525 => 136,  521 => 135,  518 => 134,  510 => 151,  506 => 128,  499 => 148,  496 => 123,  488 => 120,  484 => 144,  482 => 117,  479 => 116,  473 => 141,  469 => 111,  467 => 110,  458 => 107,  451 => 135,  448 => 104,  442 => 103,  441 => 102,  428 => 100,  405 => 127,  379 => 98,  367 => 97,  349 => 109,  347 => 95,  343 => 94,  329 => 91,  325 => 90,  315 => 84,  313 => 100,  308 => 80,  304 => 78,  298 => 76,  295 => 92,  282 => 74,  280 => 73,  269 => 72,  266 => 71,  264 => 70,  261 => 81,  253 => 80,  245 => 63,  239 => 62,  235 => 61,  230 => 59,  227 => 59,  225 => 57,  222 => 56,  210 => 57,  206 => 55,  205 => 52,  202 => 51,  198 => 49,  192 => 48,  175 => 47,  173 => 46,  170 => 52,  169 => 52,  163 => 40,  157 => 50,  152 => 34,  147 => 33,  139 => 44,  137 => 26,  128 => 24,  126 => 38,  123 => 31,  110 => 22,  107 => 26,  105 => 20,  102 => 19,  96 => 18,  87 => 26,  84 => 15,  72 => 36,  66 => 11,  46 => 8,  37 => 7,  34 => 7,  26 => 6,  23 => 3,  21 => 2,  19 => 1,);
    }
}
