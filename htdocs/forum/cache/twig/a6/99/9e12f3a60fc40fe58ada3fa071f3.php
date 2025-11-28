<?php

/* ucp_footer.html */
class __TwigTemplate_a6999e12f3a60fc40fe58ada3fa071f3 extends Twig_Template
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
        echo "
\t\t</div>

\t</div>
\t</div>
</div>
";
        // line 7
        if ((isset($context["S_COMPOSE_PM"]) ? $context["S_COMPOSE_PM"] : null)) {
            // line 8
            echo "<div>";
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "</div>
</form>
";
        }
        // line 11
        echo "
";
        // line 12
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
        // line 13
        echo "
";
        // line 14
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
        return "ucp_footer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 12,  322 => 101,  305 => 99,  301 => 98,  298 => 97,  297 => 96,  290 => 94,  287 => 93,  284 => 92,  278 => 90,  271 => 88,  269 => 87,  265 => 86,  257 => 84,  252 => 83,  243 => 80,  237 => 78,  228 => 75,  226 => 74,  218 => 72,  209 => 70,  203 => 68,  197 => 66,  193 => 65,  176 => 57,  172 => 55,  170 => 54,  167 => 53,  136 => 42,  125 => 33,  110 => 30,  102 => 28,  74 => 21,  66 => 19,  62 => 18,  58 => 17,  25 => 5,  650 => 189,  646 => 187,  640 => 184,  635 => 183,  632 => 182,  630 => 181,  627 => 180,  623 => 178,  617 => 176,  615 => 175,  609 => 174,  604 => 173,  600 => 171,  598 => 170,  595 => 169,  589 => 166,  583 => 165,  578 => 164,  575 => 163,  573 => 162,  570 => 161,  547 => 158,  538 => 157,  535 => 156,  533 => 155,  530 => 154,  526 => 152,  524 => 151,  521 => 150,  511 => 148,  508 => 147,  500 => 145,  497 => 144,  489 => 142,  486 => 141,  478 => 139,  475 => 138,  467 => 136,  464 => 135,  456 => 133,  453 => 132,  445 => 130,  442 => 129,  441 => 128,  435 => 124,  433 => 123,  430 => 122,  425 => 119,  419 => 116,  416 => 115,  413 => 114,  406 => 110,  402 => 108,  400 => 107,  396 => 106,  386 => 104,  384 => 103,  381 => 102,  371 => 95,  362 => 94,  355 => 93,  349 => 92,  345 => 91,  341 => 90,  336 => 87,  334 => 86,  331 => 85,  326 => 82,  324 => 81,  319 => 79,  315 => 77,  314 => 76,  300 => 73,  296 => 71,  295 => 70,  291 => 68,  288 => 67,  267 => 64,  264 => 63,  262 => 62,  255 => 59,  250 => 57,  246 => 81,  239 => 54,  224 => 50,  222 => 73,  214 => 71,  211 => 46,  208 => 45,  189 => 64,  178 => 40,  171 => 37,  156 => 35,  155 => 34,  149 => 32,  132 => 30,  128 => 29,  126 => 28,  114 => 31,  106 => 29,  100 => 21,  91 => 17,  83 => 15,  81 => 14,  78 => 22,  50 => 9,  42 => 8,  33 => 5,  28 => 3,  281 => 66,  277 => 81,  272 => 65,  268 => 76,  261 => 85,  253 => 73,  241 => 55,  238 => 71,  230 => 52,  227 => 51,  221 => 65,  219 => 64,  206 => 63,  202 => 61,  199 => 60,  192 => 55,  185 => 62,  166 => 51,  163 => 50,  152 => 52,  148 => 44,  146 => 31,  130 => 36,  119 => 35,  108 => 33,  104 => 23,  94 => 26,  92 => 27,  89 => 26,  85 => 25,  82 => 23,  77 => 21,  76 => 20,  69 => 19,  68 => 18,  64 => 17,  54 => 14,  51 => 13,  49 => 12,  46 => 11,  43 => 10,  37 => 7,  32 => 6,  29 => 8,  27 => 7,  350 => 98,  343 => 93,  325 => 102,  321 => 80,  317 => 89,  311 => 85,  309 => 84,  306 => 83,  299 => 78,  280 => 76,  276 => 89,  273 => 74,  251 => 72,  247 => 71,  242 => 69,  236 => 65,  234 => 77,  229 => 61,  223 => 60,  220 => 59,  212 => 57,  204 => 55,  201 => 67,  198 => 53,  190 => 50,  186 => 63,  180 => 41,  175 => 39,  159 => 36,  140 => 41,  135 => 40,  131 => 39,  127 => 37,  117 => 34,  109 => 25,  101 => 22,  99 => 29,  96 => 28,  93 => 27,  88 => 26,  86 => 24,  75 => 16,  67 => 14,  65 => 13,  60 => 16,  194 => 52,  191 => 42,  177 => 41,  174 => 52,  160 => 39,  157 => 38,  143 => 46,  137 => 38,  124 => 32,  121 => 36,  113 => 25,  111 => 34,  98 => 27,  97 => 20,  90 => 25,  87 => 17,  73 => 10,  70 => 20,  56 => 14,  53 => 13,  45 => 8,  41 => 7,  36 => 11,  34 => 3,  31 => 4,  38 => 11,  24 => 3,  22 => 2,  19 => 1,);
    }
}
