<?php

/* _style_config.html */
class __TwigTemplate_491950b63537aadc49f77495ceae2537 extends Twig_Template
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
        if ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "FALSE")) {
            // line 2
            echo "\tVariables below change style behavior.

\tList of variables and values (do not edit!):
\t\tForums list layout:
\t\t\t\$STANDARD_FORUMS_LAYOUT = 0\t-> Layout with topics and posts below forum title
\t\t\t\$STANDARD_FORUMS_LAYOUT = 1\t-> Default layout with separate columns for topics and posts

\t\tHide forum description:
\t\t\t\$HIDE_FORUM_DESCRIPTION = 0 -> Always show it
\t\t\t\$HIDE_FORUM_DESCRIPTION = 1 -> Show it only when hovering forum title

\t\tWrap header / navigation:
\t\t\t\$WRAP_HEADER = 0 -> Header and navigation will not be included in global wrapper
\t\t\t\$WRAP_HEADER = 1 -> Both header and navigation will be included in global wrapper
\t\t\t\$WRAP_HEADER = 2 -> Header will not be included in global wrapper, navigation will be included

\t\tWrap footer:
\t\t\t\$WRAP_FOOTER = 0 -> Footer will be outside of content wrapper
\t\t\t\$WRAP_FOOTER = 1 -> Footer will be inside content wrapper

\t\tQuick search position:
\t\t\t\$SEARCH_IN_NAVBAR = 0 -> Search bar will be displayed in header
\t\t\t\$SEARCH_IN_NAVBAR = 1 -> Search bar will be displayed in secondary navigation

\tEdit variables below:
";
        }
        // line 28
        echo "
";
        // line 29
        $value = 1;
        $context['definition']->set('STANDARD_FORUMS_LAYOUT', $value);
        // line 30
        $value = 0;
        $context['definition']->set('HIDE_FORUM_DESCRIPTION', $value);
        // line 31
        $value = 2;
        $context['definition']->set('WRAP_HEADER', $value);
        // line 32
        $value = 0;
        $context['definition']->set('WRAP_FOOTER', $value);
        // line 33
        $value = 0;
        $context['definition']->set('SEARCH_IN_NAVBAR', $value);
        // line 34
        echo "
";
        // line 35
        if ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "FALSE")) {
            // line 36
            echo "\tDo not edit code below!
";
        }
    }

    public function getTemplateName()
    {
        return "_style_config.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 35,  67 => 34,  64 => 33,  61 => 32,  58 => 31,  55 => 30,  52 => 29,  49 => 28,  1501 => 409,  1498 => 408,  1492 => 405,  1480 => 404,  1477 => 403,  1475 => 402,  1472 => 401,  1460 => 400,  1459 => 399,  1454 => 396,  1450 => 394,  1444 => 392,  1441 => 391,  1428 => 390,  1426 => 389,  1422 => 388,  1419 => 387,  1417 => 386,  1414 => 385,  1408 => 381,  1393 => 379,  1389 => 378,  1385 => 377,  1376 => 373,  1369 => 372,  1367 => 371,  1364 => 370,  1352 => 369,  1348 => 367,  1347 => 366,  1344 => 365,  1340 => 363,  1334 => 362,  1317 => 361,  1315 => 360,  1312 => 359,  1311 => 358,  1307 => 356,  1306 => 355,  1303 => 354,  1297 => 350,  1292 => 348,  1284 => 347,  1276 => 346,  1274 => 345,  1268 => 343,  1266 => 342,  1263 => 341,  1249 => 339,  1247 => 338,  1244 => 337,  1239 => 335,  1231 => 332,  1224 => 327,  1223 => 326,  1220 => 325,  1211 => 324,  1209 => 323,  1203 => 322,  1200 => 321,  1196 => 319,  1187 => 318,  1183 => 317,  1180 => 316,  1176 => 314,  1167 => 313,  1163 => 312,  1160 => 311,  1157 => 310,  1150 => 309,  1149 => 308,  1146 => 307,  1142 => 305,  1133 => 303,  1129 => 302,  1124 => 300,  1120 => 298,  1118 => 297,  1113 => 295,  1110 => 294,  1102 => 291,  1099 => 290,  1097 => 289,  1094 => 288,  1087 => 284,  1083 => 283,  1079 => 282,  1075 => 281,  1071 => 280,  1065 => 278,  1058 => 274,  1054 => 273,  1050 => 272,  1046 => 271,  1042 => 270,  1036 => 268,  1034 => 267,  1015 => 265,  1012 => 264,  1009 => 263,  1005 => 261,  1003 => 260,  993 => 257,  990 => 256,  987 => 255,  977 => 252,  974 => 251,  971 => 250,  961 => 247,  958 => 246,  955 => 245,  945 => 242,  942 => 241,  939 => 240,  929 => 237,  926 => 236,  923 => 235,  913 => 232,  910 => 231,  907 => 230,  906 => 229,  903 => 228,  900 => 227,  898 => 226,  876 => 224,  866 => 222,  863 => 221,  857 => 218,  853 => 217,  848 => 216,  842 => 213,  838 => 212,  833 => 211,  830 => 210,  828 => 209,  821 => 204,  819 => 203,  812 => 198,  806 => 197,  802 => 195,  800 => 194,  793 => 192,  775 => 191,  771 => 189,  768 => 188,  764 => 187,  761 => 186,  757 => 185,  748 => 181,  744 => 180,  739 => 179,  736 => 178,  733 => 177,  732 => 176,  729 => 175,  727 => 174,  721 => 173,  710 => 171,  707 => 170,  702 => 169,  701 => 168,  698 => 167,  690 => 165,  687 => 164,  685 => 163,  682 => 162,  672 => 161,  662 => 160,  645 => 159,  642 => 158,  632 => 157,  628 => 155,  626 => 154,  617 => 153,  616 => 152,  613 => 151,  611 => 150,  608 => 149,  595 => 148,  592 => 147,  591 => 146,  576 => 144,  568 => 143,  540 => 140,  530 => 138,  527 => 137,  525 => 136,  521 => 135,  518 => 134,  510 => 129,  506 => 128,  499 => 124,  496 => 123,  488 => 120,  484 => 118,  482 => 117,  479 => 116,  473 => 113,  469 => 111,  467 => 110,  458 => 107,  451 => 105,  448 => 104,  442 => 103,  441 => 102,  428 => 100,  405 => 99,  379 => 98,  367 => 97,  349 => 96,  347 => 95,  343 => 94,  329 => 91,  325 => 90,  315 => 84,  313 => 83,  308 => 80,  304 => 78,  298 => 76,  295 => 75,  282 => 74,  280 => 73,  269 => 72,  266 => 71,  264 => 70,  261 => 69,  253 => 64,  245 => 63,  239 => 62,  235 => 61,  230 => 59,  227 => 58,  225 => 57,  222 => 56,  210 => 55,  206 => 53,  205 => 52,  202 => 51,  198 => 49,  192 => 48,  175 => 47,  173 => 46,  170 => 45,  169 => 44,  163 => 40,  157 => 36,  152 => 34,  147 => 33,  139 => 31,  137 => 30,  128 => 27,  126 => 26,  123 => 25,  110 => 22,  107 => 21,  105 => 20,  102 => 19,  96 => 18,  87 => 16,  84 => 15,  72 => 36,  66 => 11,  46 => 8,  37 => 7,  34 => 6,  26 => 4,  23 => 3,  21 => 2,  19 => 1,);
    }
}
