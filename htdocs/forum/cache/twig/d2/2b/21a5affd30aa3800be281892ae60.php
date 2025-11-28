<?php

/* notification_dropdown.html */
class __TwigTemplate_d22b21a5affd30aa3800be281892ae60 extends Twig_Template
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
        echo "<div id=\"notification_list\" class=\"dropdown dropdown-extended notification_list\">
\t<div class=\"pointer\"><div class=\"pointer-inner\"></div></div>
\t<div class=\"dropdown-contents\">
\t\t<div class=\"header\">
\t\t\t";
        // line 5
        echo $this->env->getExtension('phpbb')->lang("NOTIFICATIONS");
        echo "
\t\t\t<span class=\"header_settings\">
\t\t\t\t<a href=\"";
        // line 7
        echo (isset($context["U_NOTIFICATION_SETTINGS"]) ? $context["U_NOTIFICATION_SETTINGS"] : null);
        echo "\">";
        echo $this->env->getExtension('phpbb')->lang("SETTINGS");
        echo "</a>
\t\t\t\t";
        // line 8
        if ((isset($context["NOTIFICATIONS_COUNT"]) ? $context["NOTIFICATIONS_COUNT"] : null)) {
            // line 9
            echo "\t\t\t\t\t<span id=\"mark_all_notifications\"> &bull; <a href=\"";
            echo (isset($context["U_MARK_ALL_NOTIFICATIONS"]) ? $context["U_MARK_ALL_NOTIFICATIONS"] : null);
            echo "\" data-ajax=\"notification.mark_all_read\">";
            echo $this->env->getExtension('phpbb')->lang("MARK_ALL_READ");
            echo "</a></span>
\t\t\t\t";
        }
        // line 11
        echo "\t\t\t</span>
\t\t</div>

\t\t<ul>
\t\t\t";
        // line 15
        if ((!twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notifications")))) {
            // line 16
            echo "\t\t\t\t<li class=\"no_notifications\">
\t\t\t\t\t";
            // line 17
            echo $this->env->getExtension('phpbb')->lang("NO_NOTIFICATIONS");
            echo "
\t\t\t\t</li>
\t\t\t";
        }
        // line 20
        echo "\t\t\t";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notifications"));
        foreach ($context['_seq'] as $context["_key"] => $context["notifications"]) {
            // line 21
            echo "\t\t\t\t<li class=\"dropdown-notification ";
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "UNREAD")) {
                echo " bg3";
            }
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "STYLING")) {
                echo " ";
                echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "STYLING");
            }
            if ((!$this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "URL"))) {
                echo " no-url";
            }
            echo "\">
\t\t\t\t\t";
            // line 22
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "URL")) {
                // line 23
                echo "\t\t\t\t\t\t<a class=\"notification-block\" href=\"";
                if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "UNREAD")) {
                    echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "U_MARK_READ");
                    echo "\" data-real-url=\"";
                    echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "URL");
                } else {
                    echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "URL");
                }
                echo "\">
\t\t\t\t\t";
            }
            // line 25
            echo "\t\t\t\t\t\t";
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "AVATAR")) {
                echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "AVATAR");
            } else {
                echo "<img src=\"";
                echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
                echo "/images/no_avatar.png\" alt=\"\" />";
            }
            // line 26
            echo "\t\t\t\t\t\t<div class=\"notification_text\">
\t\t\t\t\t\t\t<p class=\"notification-title\">";
            // line 27
            echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "FORMATTED_TITLE");
            echo "</p>
\t\t\t\t\t\t\t";
            // line 28
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "REFERENCE")) {
                echo "<p class=\"notification-reference\">";
                echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "REFERENCE");
                echo "</p>";
            }
            // line 29
            echo "\t\t\t\t\t\t\t";
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "FORUM")) {
                echo "<p class=\"notification-forum\">";
                echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "FORUM");
                echo "</p>";
            }
            // line 30
            echo "\t\t\t\t\t\t\t";
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "REASON")) {
                echo "<p class=\"notification-reason\">";
                echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "REASON");
                echo "</p>";
            }
            // line 31
            echo "\t\t\t\t\t\t\t<p class=\"notification-time\">";
            echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "TIME");
            echo "</p>
\t\t\t\t\t\t</div>
\t\t\t\t\t";
            // line 33
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "URL")) {
                echo "</a>";
            }
            // line 34
            echo "\t\t\t\t\t";
            if ($this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "UNREAD")) {
                // line 35
                echo "\t\t\t\t\t\t<a href=\"";
                echo $this->getAttribute((isset($context["notifications"]) ? $context["notifications"] : null), "U_MARK_READ");
                echo "\" class=\"mark_read icon-mark\" data-ajax=\"notification.mark_read\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("MARK_READ");
                echo "\"></a>
\t\t\t\t\t";
            }
            // line 37
            echo "\t\t\t\t</li>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notifications'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "\t\t</ul>

\t\t<div class=\"footer\">
\t\t\t<a href=\"";
        // line 42
        echo (isset($context["U_VIEW_ALL_NOTIFICATIONS"]) ? $context["U_VIEW_ALL_NOTIFICATIONS"] : null);
        echo "\"><span>";
        echo $this->env->getExtension('phpbb')->lang("SEE_ALL");
        echo "</span></a>
\t\t</div>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "notification_dropdown.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  160 => 39,  145 => 35,  142 => 34,  118 => 29,  112 => 28,  105 => 26,  96 => 25,  84 => 23,  82 => 22,  54 => 16,  46 => 11,  38 => 9,  30 => 7,  25 => 5,  657 => 193,  652 => 192,  650 => 191,  644 => 188,  634 => 186,  632 => 185,  629 => 184,  628 => 183,  625 => 182,  623 => 181,  617 => 180,  616 => 179,  603 => 178,  601 => 177,  597 => 176,  588 => 175,  586 => 174,  576 => 173,  573 => 172,  571 => 171,  568 => 170,  564 => 168,  562 => 167,  557 => 164,  551 => 162,  549 => 161,  546 => 160,  543 => 159,  535 => 157,  532 => 156,  524 => 154,  522 => 153,  513 => 152,  510 => 151,  507 => 150,  499 => 148,  497 => 147,  490 => 146,  487 => 145,  484 => 144,  481 => 143,  473 => 141,  471 => 140,  464 => 139,  462 => 138,  457 => 137,  454 => 136,  451 => 135,  419 => 132,  416 => 131,  410 => 129,  405 => 127,  403 => 126,  396 => 124,  389 => 119,  376 => 115,  374 => 114,  365 => 113,  355 => 111,  352 => 110,  349 => 109,  345 => 107,  333 => 106,  327 => 103,  313 => 100,  310 => 99,  302 => 94,  295 => 92,  288 => 91,  275 => 86,  271 => 84,  270 => 83,  253 => 80,  249 => 78,  246 => 77,  229 => 72,  223 => 70,  213 => 67,  207 => 65,  196 => 64,  186 => 61,  182 => 59,  178 => 57,  153 => 37,  144 => 46,  141 => 45,  139 => 44,  125 => 30,  120 => 34,  113 => 32,  92 => 27,  87 => 26,  79 => 24,  68 => 21,  66 => 20,  63 => 20,  57 => 17,  47 => 11,  39 => 8,  32 => 5,  23 => 3,  70 => 35,  64 => 33,  61 => 18,  58 => 17,  55 => 30,  52 => 15,  49 => 28,  21 => 2,  449 => 134,  446 => 133,  436 => 116,  432 => 114,  430 => 113,  426 => 112,  421 => 109,  420 => 108,  417 => 107,  413 => 105,  411 => 104,  407 => 128,  395 => 101,  392 => 100,  386 => 118,  384 => 117,  378 => 91,  375 => 90,  370 => 89,  361 => 84,  353 => 83,  347 => 82,  339 => 81,  334 => 79,  331 => 78,  328 => 77,  326 => 76,  320 => 101,  316 => 72,  312 => 71,  294 => 70,  286 => 90,  282 => 62,  280 => 61,  277 => 60,  276 => 59,  264 => 56,  260 => 54,  259 => 53,  254 => 51,  251 => 50,  250 => 79,  242 => 46,  238 => 76,  228 => 41,  225 => 40,  217 => 38,  215 => 37,  210 => 66,  204 => 33,  198 => 31,  194 => 29,  192 => 28,  189 => 27,  185 => 26,  179 => 25,  176 => 56,  170 => 52,  168 => 21,  165 => 42,  147 => 18,  121 => 16,  110 => 15,  99 => 29,  88 => 13,  77 => 12,  75 => 11,  72 => 36,  60 => 9,  35 => 7,  31 => 6,  22 => 2,  261 => 81,  256 => 65,  245 => 59,  234 => 57,  230 => 42,  226 => 71,  220 => 51,  218 => 50,  212 => 46,  209 => 45,  195 => 63,  193 => 43,  183 => 60,  177 => 35,  174 => 34,  167 => 31,  161 => 51,  158 => 29,  155 => 28,  152 => 27,  138 => 33,  134 => 25,  132 => 31,  129 => 23,  126 => 38,  117 => 21,  108 => 27,  106 => 30,  100 => 18,  93 => 17,  83 => 14,  76 => 23,  73 => 12,  67 => 34,  59 => 10,  50 => 12,  36 => 8,  33 => 6,  19 => 1,);
    }
}
