<?php

/* overall_footer.html */
class __TwigTemplate_4024b0cd396f9d3202e9fee74a42e66b extends Twig_Template
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
        echo "\t\t\t\t\t\t</div>
\t\t\t\t\t</div><!-- /#main -->
\t\t\t\t</div>
\t\t</div><!-- /#acp -->
\t</div>

\t<div id=\"page-footer\">
\t\t<div class=\"copyright\">
\t\t\t";
        // line 9
        if ((isset($context["S_COPYRIGHT_HTML"]) ? $context["S_COPYRIGHT_HTML"] : null)) {
            // line 10
            echo "\t\t\t\t";
            echo (isset($context["CREDIT_LINE"]) ? $context["CREDIT_LINE"] : null);
            echo "
\t\t\t\t";
            // line 11
            if ((isset($context["TRANSLATION_INFO"]) ? $context["TRANSLATION_INFO"] : null)) {
                echo "<br />";
                echo (isset($context["TRANSLATION_INFO"]) ? $context["TRANSLATION_INFO"] : null);
            }
            // line 12
            echo "\t\t\t";
        }
        // line 13
        echo "
\t\t\t";
        // line 14
        if ((isset($context["DEBUG_OUTPUT"]) ? $context["DEBUG_OUTPUT"] : null)) {
            // line 15
            echo "\t\t\t\t";
            if ((isset($context["S_COPYRIGHT_HTML"]) ? $context["S_COPYRIGHT_HTML"] : null)) {
                echo "<br />";
            }
            // line 16
            echo "\t\t\t\t";
            echo (isset($context["DEBUG_OUTPUT"]) ? $context["DEBUG_OUTPUT"] : null);
            echo "
\t\t\t";
        }
        // line 18
        echo "\t\t</div>

\t\t<div id=\"darkenwrapper\" data-ajax-error-title=\"";
        // line 20
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TITLE");
        echo "\" data-ajax-error-text=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT");
        echo "\" data-ajax-error-text-abort=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT_ABORT");
        echo "\" data-ajax-error-text-timeout=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT_TIMEOUT");
        echo "\" data-ajax-error-text-parsererror=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT_PARSERERROR");
        echo "\">
\t\t\t<div id=\"darken\">&nbsp;</div>
\t\t</div>
\t\t<div id=\"loading_indicator\"></div>

\t\t<div id=\"phpbb_alert\" class=\"phpbb_alert\" data-l-err=\"";
        // line 25
        echo $this->env->getExtension('phpbb')->lang("ERROR");
        echo "\" data-l-timeout-processing-req=\"";
        echo $this->env->getExtension('phpbb')->lang("TIMEOUT_PROCESSING_REQ");
        echo "\">
\t\t\t<a href=\"#\" class=\"alert_close\"></a>
\t\t\t<h3 class=\"alert_title\"></h3><p class=\"alert_text\"></p>
\t\t</div>
\t\t<div id=\"phpbb_confirm\" class=\"phpbb_alert\">
\t\t\t<a href=\"#\" class=\"alert_close\"></a>
\t\t\t<div class=\"alert_text\"></div>
\t\t</div>
\t</div>
</div>

<script type=\"text/javascript\" src=\"";
        // line 36
        echo (isset($context["T_JQUERY_LINK"]) ? $context["T_JQUERY_LINK"] : null);
        echo "\"></script>
";
        // line 37
        if ((isset($context["S_ALLOW_CDN"]) ? $context["S_ALLOW_CDN"] : null)) {
            echo "<script type=\"text/javascript\">window.jQuery || document.write(unescape('%3Cscript src=\"";
            echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
            echo "/javascript/jquery.min.js?assets_version=";
            echo (isset($context["T_ASSETS_VERSION"]) ? $context["T_ASSETS_VERSION"] : null);
            echo "\" type=\"text/javascript\"%3E%3C/script%3E'));</script>";
        }
        // line 38
        echo "<script type=\"text/javascript\" src=\"";
        echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
        echo "/javascript/core.js?assets_version=";
        echo (isset($context["T_ASSETS_VERSION"]) ? $context["T_ASSETS_VERSION"] : null);
        echo "\"></script>
";
        // line 39
        $asset_file = "ajax.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            $asset->add_assets_version('2');
            $asset_file = $asset->get_url();
            }
        }
        $context['definition']->append('SCRIPTS', '<script type="text/javascript" src="' . $asset_file. '"></script>

');
        // line 40
        $asset_file = "admin.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            $asset->add_assets_version('2');
            $asset_file = $asset->get_url();
            }
        }
        $context['definition']->append('SCRIPTS', '<script type="text/javascript" src="' . $asset_file. '"></script>

');
        // line 41
        echo "
";
        // line 42
        // line 43
        echo $this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "SCRIPTS");
        echo "

</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "overall_footer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  149 => 43,  130 => 40,  108 => 38,  100 => 37,  80 => 25,  64 => 20,  60 => 18,  54 => 16,  49 => 15,  47 => 14,  44 => 13,  29 => 9,  415 => 162,  410 => 159,  392 => 154,  384 => 152,  379 => 150,  373 => 148,  371 => 147,  361 => 140,  357 => 139,  347 => 133,  327 => 124,  322 => 122,  316 => 119,  306 => 112,  299 => 108,  292 => 104,  278 => 97,  258 => 80,  240 => 70,  230 => 66,  196 => 55,  172 => 50,  167 => 49,  162 => 46,  156 => 44,  148 => 42,  143 => 40,  129 => 36,  127 => 35,  120 => 32,  114 => 29,  109 => 28,  106 => 27,  98 => 24,  92 => 23,  87 => 22,  84 => 21,  82 => 20,  77 => 18,  72 => 17,  69 => 16,  63 => 13,  58 => 12,  55 => 11,  50 => 9,  42 => 8,  26 => 4,  319 => 145,  314 => 142,  311 => 141,  305 => 140,  295 => 137,  289 => 136,  286 => 135,  271 => 133,  268 => 132,  265 => 131,  262 => 130,  257 => 128,  254 => 127,  249 => 74,  246 => 125,  242 => 124,  239 => 69,  236 => 122,  231 => 121,  228 => 120,  214 => 59,  206 => 113,  187 => 110,  168 => 103,  159 => 99,  158 => 98,  151 => 96,  146 => 41,  145 => 41,  137 => 38,  136 => 88,  61 => 16,  53 => 10,  48 => 13,  35 => 7,  22 => 2,  761 => 234,  758 => 233,  754 => 231,  742 => 230,  733 => 224,  729 => 223,  722 => 222,  716 => 219,  708 => 216,  702 => 215,  698 => 214,  695 => 213,  687 => 208,  681 => 207,  677 => 206,  672 => 203,  669 => 202,  656 => 201,  654 => 200,  647 => 196,  638 => 195,  635 => 194,  629 => 191,  626 => 190,  621 => 187,  612 => 184,  608 => 183,  604 => 182,  600 => 181,  578 => 180,  575 => 179,  571 => 178,  564 => 174,  560 => 173,  556 => 172,  552 => 171,  548 => 170,  543 => 167,  541 => 166,  537 => 164,  534 => 163,  521 => 162,  519 => 161,  512 => 157,  509 => 156,  504 => 153,  498 => 150,  489 => 149,  486 => 148,  484 => 147,  479 => 144,  473 => 143,  465 => 140,  453 => 139,  429 => 138,  423 => 137,  420 => 164,  414 => 133,  411 => 132,  408 => 131,  404 => 157,  397 => 126,  394 => 155,  390 => 123,  378 => 122,  375 => 121,  367 => 116,  363 => 115,  355 => 110,  350 => 109,  345 => 107,  339 => 129,  336 => 103,  332 => 125,  320 => 100,  317 => 99,  313 => 97,  301 => 138,  298 => 95,  294 => 93,  282 => 92,  279 => 91,  275 => 134,  263 => 88,  260 => 81,  256 => 85,  244 => 72,  241 => 83,  237 => 81,  225 => 80,  222 => 79,  220 => 62,  217 => 77,  209 => 58,  200 => 71,  194 => 68,  191 => 111,  185 => 54,  181 => 63,  178 => 53,  176 => 61,  171 => 59,  164 => 102,  155 => 55,  152 => 54,  144 => 49,  140 => 90,  132 => 43,  126 => 42,  121 => 40,  115 => 39,  110 => 35,  105 => 33,  96 => 36,  93 => 30,  83 => 23,  74 => 19,  68 => 18,  62 => 16,  57 => 15,  51 => 11,  46 => 9,  41 => 12,  38 => 7,  36 => 11,  31 => 10,  19 => 1,);
    }
}
