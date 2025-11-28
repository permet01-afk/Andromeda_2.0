<?php

/* posting_buttons.html */
class __TwigTemplate_2b11f8c978a36b323d194ff7e859405f extends Twig_Template
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
<script type=\"text/javascript\">
// <![CDATA[
\tvar form_name = 'postform';
\tvar text_name = ";
        // line 5
        if ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "SIG_EDIT")) {
            echo "'signature'";
        } else {
            echo "'message'";
        }
        echo ";
\tvar load_draft = false;
\tvar upload = false;

\t// Define the bbCode tags
\tvar bbcode = new Array();
\tvar bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]','[flash=]', '[/flash]','[size=]','[/size]'";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "custom_tags"));
        foreach ($context['_seq'] as $context["_key"] => $context["custom_tags"]) {
            echo ", ";
            echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "BBCODE_NAME");
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['custom_tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        echo ");
\tvar imageTag = false;

\t// Helpline messages
\tvar help_line = {
\t\tb: '";
        // line 16
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_B_HELP"));
        echo "',
\t\ti: '";
        // line 17
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_I_HELP"));
        echo "',
\t\tu: '";
        // line 18
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_U_HELP"));
        echo "',
\t\tq: '";
        // line 19
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_Q_HELP"));
        echo "',
\t\tc: '";
        // line 20
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_C_HELP"));
        echo "',
\t\tl: '";
        // line 21
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_L_HELP"));
        echo "',
\t\to: '";
        // line 22
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_O_HELP"));
        echo "',
\t\tp: '";
        // line 23
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_P_HELP"));
        echo "',
\t\tw: '";
        // line 24
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_W_HELP"));
        echo "',
\t\ta: '";
        // line 25
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_A_HELP"));
        echo "',
\t\ts: '";
        // line 26
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_S_HELP"));
        echo "',
\t\tf: '";
        // line 27
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_F_HELP"));
        echo "',
\t\ty: '";
        // line 28
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_Y_HELP"));
        echo "',
\t\td: '";
        // line 29
        echo addslashes($this->env->getExtension('phpbb')->lang("BBCODE_D_HELP"));
        echo "'
\t\t";
        // line 30
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "custom_tags"));
        foreach ($context['_seq'] as $context["_key"] => $context["custom_tags"]) {
            // line 31
            echo "\t\t\t,cb_";
            echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "BBCODE_ID");
            echo ": '";
            echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "A_BBCODE_HELPLINE");
            echo "'
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['custom_tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        echo "\t}

\tfunction change_palette()
\t{
\t\tphpbb.toggleDisplay('colour_palette');
\t\te = document.getElementById('colour_palette');

\t\tif (e.style.display == 'block')
\t\t{
\t\t\tdocument.getElementById('bbpalette').value = '";
        // line 42
        echo addslashes($this->env->getExtension('phpbb')->lang("FONT_COLOR_HIDE"));
        echo "';
\t\t}
\t\telse
\t\t{
\t\t\tdocument.getElementById('bbpalette').value = '";
        // line 46
        echo addslashes($this->env->getExtension('phpbb')->lang("FONT_COLOR"));
        echo "';
\t\t}
\t}

// ]]>
</script>
";
        // line 52
        $asset_file = (("" . (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null)) . "/javascript/editor.js");
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
        // line 53
        echo "
";
        // line 54
        if ((isset($context["S_BBCODE_ALLOWED"]) ? $context["S_BBCODE_ALLOWED"] : null)) {
            // line 55
            echo "<div id=\"colour_palette\" style=\"display: none;\">
\t<dl style=\"clear: left;\">
\t\t<dt><label>";
            // line 57
            echo $this->env->getExtension('phpbb')->lang("FONT_COLOR");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd id=\"color_palette_placeholder\" data-orientation=\"h\" data-height=\"12\" data-width=\"15\" data-bbcode=\"true\"></dd>
\t</dl>
</div>

";
            // line 62
            // line 63
            echo "<div id=\"format-buttons\">
\t<input type=\"button\" class=\"button2 bbcode-b\" accesskey=\"b\" name=\"addbbcode0\" value=\" B \" style=\"font-weight:bold; width: 30px\" onclick=\"bbstyle(0)\" title=\"";
            // line 64
            echo $this->env->getExtension('phpbb')->lang("BBCODE_B_HELP");
            echo "\" />
\t<input type=\"button\" class=\"button2 bbcode-i\" accesskey=\"i\" name=\"addbbcode2\" value=\" i \" style=\"font-style:italic; width: 30px\" onclick=\"bbstyle(2)\" title=\"";
            // line 65
            echo $this->env->getExtension('phpbb')->lang("BBCODE_I_HELP");
            echo "\" />
\t<input type=\"button\" class=\"button2 bbcode-u\" accesskey=\"u\" name=\"addbbcode4\" value=\" u \" style=\"text-decoration: underline; width: 30px\" onclick=\"bbstyle(4)\" title=\"";
            // line 66
            echo $this->env->getExtension('phpbb')->lang("BBCODE_U_HELP");
            echo "\" />
\t";
            // line 67
            if ((isset($context["S_BBCODE_QUOTE"]) ? $context["S_BBCODE_QUOTE"] : null)) {
                // line 68
                echo "\t\t<input type=\"button\" class=\"button2 bbcode-quote\" accesskey=\"q\" name=\"addbbcode6\" value=\"Quote\" style=\"width: 50px\" onclick=\"bbstyle(6)\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("BBCODE_Q_HELP");
                echo "\" />
\t";
            }
            // line 70
            echo "\t<input type=\"button\" class=\"button2 bbcode-code\" accesskey=\"c\" name=\"addbbcode8\" value=\"Code\" style=\"width: 40px\" onclick=\"bbstyle(8)\" title=\"";
            echo $this->env->getExtension('phpbb')->lang("BBCODE_C_HELP");
            echo "\" />
\t<input type=\"button\" class=\"button2 bbcode-list\" accesskey=\"l\" name=\"addbbcode10\" value=\"List\" style=\"width: 40px\" onclick=\"bbstyle(10)\" title=\"";
            // line 71
            echo $this->env->getExtension('phpbb')->lang("BBCODE_L_HELP");
            echo "\" />
\t<input type=\"button\" class=\"button2 bbcode-list-\" accesskey=\"o\" name=\"addbbcode12\" value=\"List=\" style=\"width: 40px\" onclick=\"bbstyle(12)\" title=\"";
            // line 72
            echo $this->env->getExtension('phpbb')->lang("BBCODE_O_HELP");
            echo "\" />
\t<input type=\"button\" class=\"button2 bbcode-asterisk\" accesskey=\"y\" name=\"addlistitem\" value=\"[*]\" style=\"width: 40px\" onclick=\"bbstyle(-1)\" title=\"";
            // line 73
            echo $this->env->getExtension('phpbb')->lang("BBCODE_LISTITEM_HELP");
            echo "\" />
\t";
            // line 74
            if ((isset($context["S_BBCODE_IMG"]) ? $context["S_BBCODE_IMG"] : null)) {
                // line 75
                echo "\t\t<input type=\"button\" class=\"button2 bbcode-img\" accesskey=\"p\" name=\"addbbcode14\" value=\"Img\" style=\"width: 40px\" onclick=\"bbstyle(14)\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("BBCODE_P_HELP");
                echo "\" />
\t";
            }
            // line 77
            echo "\t";
            if ((isset($context["S_LINKS_ALLOWED"]) ? $context["S_LINKS_ALLOWED"] : null)) {
                // line 78
                echo "\t\t<input type=\"button\" class=\"button2 bbcode-url\" accesskey=\"w\" name=\"addbbcode16\" value=\"URL\" style=\"text-decoration: underline; width: 40px\" onclick=\"bbstyle(16)\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("BBCODE_W_HELP");
                echo "\" />
\t";
            }
            // line 80
            echo "\t";
            if ((isset($context["S_BBCODE_FLASH"]) ? $context["S_BBCODE_FLASH"] : null)) {
                // line 81
                echo "\t\t<input type=\"button\" class=\"button2 bbcode-flash\" accesskey=\"d\" name=\"addbbcode18\" value=\"Flash\" onclick=\"bbstyle(18)\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("BBCODE_D_HELP");
                echo "\" />
\t";
            }
            // line 83
            echo "\t<select name=\"addbbcode20\" class=\"bbcode-size\" onchange=\"bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]');this.form.addbbcode20.selectedIndex = 2;\" title=\"";
            echo $this->env->getExtension('phpbb')->lang("BBCODE_F_HELP");
            echo "\">
\t\t<option value=\"50\">";
            // line 84
            echo $this->env->getExtension('phpbb')->lang("FONT_TINY");
            echo "</option>
\t\t<option value=\"85\">";
            // line 85
            echo $this->env->getExtension('phpbb')->lang("FONT_SMALL");
            echo "</option>
\t\t<option value=\"100\" selected=\"selected\">";
            // line 86
            echo $this->env->getExtension('phpbb')->lang("FONT_NORMAL");
            echo "</option>
\t\t";
            // line 87
            if (((!(isset($context["MAX_FONT_SIZE"]) ? $context["MAX_FONT_SIZE"] : null)) || ((isset($context["MAX_FONT_SIZE"]) ? $context["MAX_FONT_SIZE"] : null) >= 150))) {
                // line 88
                echo "\t\t\t<option value=\"150\">";
                echo $this->env->getExtension('phpbb')->lang("FONT_LARGE");
                echo "</option>
\t\t\t";
                // line 89
                if (((!(isset($context["MAX_FONT_SIZE"]) ? $context["MAX_FONT_SIZE"] : null)) || ((isset($context["MAX_FONT_SIZE"]) ? $context["MAX_FONT_SIZE"] : null) >= 200))) {
                    // line 90
                    echo "\t\t\t\t<option value=\"200\">";
                    echo $this->env->getExtension('phpbb')->lang("FONT_HUGE");
                    echo "</option>
\t\t\t";
                }
                // line 92
                echo "\t\t";
            }
            // line 93
            echo "\t</select>
\t<input type=\"button\" class=\"button2 bbcode-color\" name=\"bbpalette\" id=\"bbpalette\" value=\"";
            // line 94
            echo $this->env->getExtension('phpbb')->lang("FONT_COLOR");
            echo "\" onclick=\"change_palette();\" title=\"";
            echo $this->env->getExtension('phpbb')->lang("BBCODE_S_HELP");
            echo "\" />

\t";
            // line 96
            // line 97
            echo "
\t";
            // line 98
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "custom_tags"));
            foreach ($context['_seq'] as $context["_key"] => $context["custom_tags"]) {
                // line 99
                echo "\t\t<input type=\"button\" class=\"button2 bbcode-";
                echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "BBCODE_TAG_CLEAN");
                echo "\" name=\"addbbcode";
                echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "BBCODE_ID");
                echo "\" value=\"";
                echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "BBCODE_TAG");
                echo "\" onclick=\"bbstyle(";
                echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "BBCODE_ID");
                echo ")\" title=\"";
                echo $this->getAttribute((isset($context["custom_tags"]) ? $context["custom_tags"] : null), "BBCODE_HELPLINE");
                echo "\" />
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['custom_tags'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 101
            echo "</div>
";
            // line 102
        }
    }

    public function getTemplateName()
    {
        return "posting_buttons.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  322 => 101,  305 => 99,  301 => 98,  298 => 97,  297 => 96,  290 => 94,  287 => 93,  284 => 92,  278 => 90,  271 => 88,  269 => 87,  265 => 86,  257 => 84,  252 => 83,  243 => 80,  237 => 78,  228 => 75,  226 => 74,  218 => 72,  209 => 70,  203 => 68,  197 => 66,  193 => 65,  176 => 57,  172 => 55,  170 => 54,  167 => 53,  136 => 42,  125 => 33,  110 => 30,  102 => 28,  74 => 21,  66 => 19,  62 => 18,  58 => 17,  25 => 5,  650 => 189,  646 => 187,  640 => 184,  635 => 183,  632 => 182,  630 => 181,  627 => 180,  623 => 178,  617 => 176,  615 => 175,  609 => 174,  604 => 173,  600 => 171,  598 => 170,  595 => 169,  589 => 166,  583 => 165,  578 => 164,  575 => 163,  573 => 162,  570 => 161,  547 => 158,  538 => 157,  535 => 156,  533 => 155,  530 => 154,  526 => 152,  524 => 151,  521 => 150,  511 => 148,  508 => 147,  500 => 145,  497 => 144,  489 => 142,  486 => 141,  478 => 139,  475 => 138,  467 => 136,  464 => 135,  456 => 133,  453 => 132,  445 => 130,  442 => 129,  441 => 128,  435 => 124,  433 => 123,  430 => 122,  425 => 119,  419 => 116,  416 => 115,  413 => 114,  406 => 110,  402 => 108,  400 => 107,  396 => 106,  386 => 104,  384 => 103,  381 => 102,  371 => 95,  362 => 94,  355 => 93,  349 => 92,  345 => 91,  341 => 90,  336 => 87,  334 => 86,  331 => 85,  326 => 82,  324 => 81,  319 => 79,  315 => 77,  314 => 76,  300 => 73,  296 => 71,  295 => 70,  291 => 68,  288 => 67,  267 => 64,  264 => 63,  262 => 62,  255 => 59,  250 => 57,  246 => 81,  239 => 54,  224 => 50,  222 => 73,  214 => 71,  211 => 46,  208 => 45,  189 => 64,  178 => 40,  171 => 37,  156 => 35,  155 => 34,  149 => 32,  132 => 30,  128 => 29,  126 => 28,  114 => 31,  106 => 29,  100 => 21,  91 => 17,  83 => 15,  81 => 14,  78 => 22,  50 => 9,  42 => 8,  33 => 5,  28 => 3,  281 => 66,  277 => 81,  272 => 65,  268 => 76,  261 => 85,  253 => 73,  241 => 55,  238 => 71,  230 => 52,  227 => 51,  221 => 65,  219 => 64,  206 => 63,  202 => 61,  199 => 60,  192 => 55,  185 => 62,  166 => 51,  163 => 50,  152 => 52,  148 => 44,  146 => 31,  130 => 36,  119 => 35,  108 => 33,  104 => 23,  94 => 26,  92 => 27,  89 => 26,  85 => 25,  82 => 23,  77 => 21,  76 => 20,  69 => 19,  68 => 18,  64 => 17,  54 => 16,  51 => 13,  49 => 12,  46 => 11,  43 => 10,  37 => 7,  32 => 6,  29 => 5,  27 => 4,  350 => 98,  343 => 93,  325 => 102,  321 => 80,  317 => 89,  311 => 85,  309 => 84,  306 => 83,  299 => 78,  280 => 76,  276 => 89,  273 => 74,  251 => 72,  247 => 71,  242 => 69,  236 => 65,  234 => 77,  229 => 61,  223 => 60,  220 => 59,  212 => 57,  204 => 55,  201 => 67,  198 => 53,  190 => 50,  186 => 63,  180 => 41,  175 => 39,  159 => 36,  140 => 41,  135 => 40,  131 => 39,  127 => 37,  117 => 34,  109 => 25,  101 => 22,  99 => 29,  96 => 28,  93 => 27,  88 => 26,  86 => 24,  75 => 16,  67 => 14,  65 => 13,  60 => 16,  194 => 52,  191 => 42,  177 => 41,  174 => 52,  160 => 39,  157 => 38,  143 => 46,  137 => 38,  124 => 32,  121 => 36,  113 => 25,  111 => 34,  98 => 27,  97 => 20,  90 => 25,  87 => 17,  73 => 10,  70 => 20,  56 => 14,  53 => 13,  45 => 8,  41 => 7,  36 => 6,  34 => 3,  31 => 4,  38 => 11,  24 => 3,  22 => 2,  19 => 1,);
    }
}
